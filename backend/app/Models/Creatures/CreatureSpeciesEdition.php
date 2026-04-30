<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\Conditions\ConditionInstanceType;
use App\Enums\Creatures\CreatureSizeUnit;
use App\Enums\GameEdition;
use App\Enums\Movement\MovementType;
use App\Models\AbstractModel;
use App\Models\Conditions\ConditionInstance;
use App\Models\Dice\DiceFormula;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeed;
use App\Models\Reference;
use App\Models\Sources\Source;
use App\Models\Text\TextEntry;
use App\Traits\WithTextEntries;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\UniqueConstraintViolationException;
use Laravel\Scout\Searchable;

/**
 * @property string $id
 *
 * @property Collection<CreatureAge> $ages
 * @property CreatureType $creatureType
 * @property GameEdition $game_edition
 * @property ?int $height
 * @property ?DiceFormula $height_modifier
 * @property ?int $hit_die_faces
 * @property Collection<CreatureLanguage> $languages
 * @property Collection<MovementSpeed> $movementSpeeds
 * @property Collection<Reference> $references
 * @property Collection<CreatureSizeUnit> $sizes
 * @property Collection<CreatureSense> $senses
 * @property ?Source $source
 * @property ?int $weight
 * @property ?DiceFormula $weight_modifier
 */
class CreatureSpeciesEdition extends AbstractModel
{
    use HasUuids;
    use Searchable;
    use WithTextEntries;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'game_edition' => GameEdition::class,
            'height_modifier' => DiceFormula::class,
            'sizes' => AsEnumCollection::of(CreatureSizeUnit::class),
            'weight_modifier' => DiceFormula::class,
        ];
    }

    public function ages(): HasMany
    {
        return $this->hasMany(CreatureAge::class);
    }

    public function creatureType(): BelongsTo
    {
        return $this->belongsTo(CreatureType::class, 'creature_type_id');
    }

    public function languages(): MorphMany
    {
        return $this->morphMany(CreatureLanguage::class, 'entity');
    }

    public function movementSpeeds(): MorphMany
    {
        return $this->morphMany(MovementSpeed::class, 'parent');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function senses(): MorphMany
    {
        return $this->morphMany(CreatureSense::class, 'parent');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $parent->refresh();

        /**
         * Game Edition and source.
         */
        if (!empty($value['srd'])) {
            $gameEdition = GameEdition::FIFTH;
        } elseif (!empty($value['srd52'])) {
            $gameEdition = GameEdition::FIFTH_REVISED;
        } else {
            if (empty($value['source'])) {
                throw new \InvalidArgumentException('CreatureType edition must have a source.');
            }
            try {
                /** @var Source $source */
                $source = Source::query()->where('short_name', $value['source'])->firstOrFail();

                // Try to infer the game edition from the sourcebook.
                $gameEdition = $source->primaryEdition->game_edition ??
                    throw new \InvalidArgumentException(
                        "Could not infer game edition from sourcebook: {$source->name}"
                    );
            } catch (ModelNotFoundException $e) {
                // We can't find any source with this name so assume fifth edition.
                print "[WARNING] CreatureType edition source not found: " . $value['source'] . "\n";
                $gameEdition = GameEdition::FIFTH;
            }
        }

        $item->source()->associate($source ?? null);
        $item->game_edition = $gameEdition;
        $item->save();

        /**
         * Ages.
         */
        foreach ($value['age'] ?? [] as $ageType => $ageItem) {
            try {
                $age = CreatureAge::from5eJson([
                    'type' => $ageType,
                    'value' => $ageItem,
                ], $item);

                $item->ages()->save($age);
            } catch (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for age {$ageType}\n";
            }
        }

        /**
         * Height and Weight.
         */
        if (!empty($value['heightAndWeight'])) {
            $item->height = $value['heightAndWeight']['height'] ?? $value['heightAndWeight']['baseHeight'] ?? null;
            if (!empty($value['heightAndWeight']['heightMod'])) {
                $item->height_modifier = $value['heightAndWeight']['heightMod'];
            }
            $item->weight = $value['heightAndWeight']['weight'] ?? $value['heightAndWeight']['baseWeight'] ?? null;
            if (!empty($value['heightAndWeight']['weightMod'])) {
                $item->weight_modifier = $value['heightAndWeight']['weightMod'];
            }
        }

        /**
         * Immunities and resistances.
         */
        foreach ($value['conditionImmune'] ?? [] as $conditionItem) {
            try {
                // Correct for a weird exception where "diseased" is called "disease" in the 5e.tools data. Even though
                // "diseased" is not a 5e condition.
                if ($conditionItem === 'disease') {
                    $conditionItem = 'diseased';
                }

                $instance = ConditionInstance::from5eJson([
                    'name' => $conditionItem,
                    'type' => ConditionInstanceType::STATUS_IMMUNITY,
                ], $item);
                $item->conditionInstances()->save($instance);
            } catch (ModelNotFoundException $e) {
                print("[WARNING] Could not find StatusCondition: {$conditionItem}\n");
            }
        }

        foreach (['immune', 'resist', 'vulnerable'] as $damageType) {
            foreach ($value[$damageType] ?? [] as $damageTypeItem) {
                if (is_string($damageTypeItem)) {
                    // The condition is just a single string.
                    $instance = ConditionInstance::from5eJson([
                        'name' => $damageTypeItem,
                        'type' => ConditionInstanceType::tryFromString($damageType),
                    ], $item);
                } else {
                    // The condition is an object eg
                    // array{
                    //   resist: string[] <-- condition names here
                    //   note: ?string
                    //   cond: ?bool
                    foreach ($damageTypeItem[$damageType] ?? [] as $innerItem) {
                        $instance = ConditionInstance::from5eJson([
                            'name' => $innerItem,
                            'type' => ConditionInstanceType::tryFromString($damageType),
                            'note' => $damageTypeItem['note'] ?? null,
                            'nonmagical' => str_contains($damageTypeItem['note'], 'nonmagical'),
                        ], $item);
                    }
                }
            }
        }

        /**
         * Languages.
         */
        if (!empty($value['languageProficiencies'])) {
            foreach ($value['languageProficiencies'] as $languageGroup) {
                foreach (array_keys($languageGroup) as $languageItem) {
                    $languageItem = CreatureLanguage::from5eJson([
                        'language' => $languageItem,
                    ], $item);
                    $item->languages()->save($languageItem);
                }
            }
        }

        /**
         * Movement speeds.
         */
        if (!empty($value['speed'])) {
            if (is_array($value['speed'])) {
                // Speed is an array of speeds, plus maybe the "canHover" key.
                $canHover = !empty($value['speed']['canHover']) && $value['speed']['canHover'] === true;

                foreach ($value['speed'] as $speedType => $speedItem) {
                    // Make sure speed type is valid, because sometimes we have a "canHover" key.
                    $movementSpeedType = MovementType::tryFromString($speedType);
                    if (empty($movementSpeedType)) {
                        continue;
                    }

                    // Do we already have a speed of this type?
                    if ($item->movementSpeeds->contains('type', $movementSpeedType)) {
                        continue;
                    }

                    // Sometimes $speedItem is not a number, it's another array.
                    $speedValue = is_array($speedItem) ? $speedItem['number'] : $speedItem;

                    if (mb_strtolower($speedType) === 'fly') {
                        // Special case for fly speeds - add canHover flag.
                        $movementSpeed = MovementSpeed::from5eJson([
                            'type' => 'fly',
                            'value' => $speedValue,
                            'canHover' => $canHover,
                        ], $item);
                    } else {
                        // A movement speed other than fly.
                        $movementSpeed = MovementSpeed::from5eJson([
                            'type' => $speedType,
                            'value' => $speedValue,
                        ], $item);
                    }

                    $item->movementSpeeds()->save($movementSpeed);
                }
            } elseif (!$item->movementSpeeds->contains('type', MovementType::WALK)) {
                // Speed is just a single number. Assume it's walking.
                $movementSpeed = MovementSpeed::from5eJson([
                    'type' => 'walk',
                    'value' => $value['speed'],
                ], $item);
                $item->movementSpeeds()->save($movementSpeed);
            }
        }

        /**
         * Senses.
         */
        foreach ($value['senses'] ?? [] as $senseItem) {
            try {
                $sense = CreatureSense::from5eJson($senseItem, $item);
                $item->senses()->save($sense);
            } catch (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for sense {$senseItem}\n";
            }
        }
        // Sometimes the key is just in the $value array instead of a "senses" array.
        if (!empty($value['darkvision']) && empty($item->darkvision)) {
            $darkvision = CreatureSense::from5eJson([
                'type' => 'darkvision',
                'value' => (int) $value['darkvision'],
            ], $item);
            $item->senses()->save($darkvision);
        }

        /**
         * Text Entries.
         */
        foreach ($value['entries'] ?? [] as $textEntry) {
            $entry = TextEntry::fromInternalJson($textEntry, $item);
            $item->textEntries()->save($entry);
        }

        // Special case for "sizeEntry".
        if (!empty($value['sizeEntry'])) {
            $value['sizeEntry']['type'] = 'entries';
            $entry = TextEntry::fromInternalJson($value['sizeEntry'], $item);
            $item->textEntries()->save($entry);
        }

        $item->save();
        return $item;
    }
}
