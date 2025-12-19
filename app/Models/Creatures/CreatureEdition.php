<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\Creatures\CreatureSizeUnit;
use App\Enums\DamageType;
use App\Enums\GameEdition;
use App\Enums\SenseType;
use App\Models\AbilityScores\AbilityScore;
use App\Models\AbilityScores\AbilityScoreModifierGroup;
use App\Models\AbstractModel;
use App\Models\ArmorClass\ArmorClass;
use App\Models\Dice\DiceFormula;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeedGroup;
use App\Models\Reference;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\ItemNotFoundException;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property AbilityScoreModifierGroup $abilityScoreModifiers
 * @property Collection<CreatureAge> $ages
 * @property ?ArmorClass $armorClass
 * @property ?int $challenge_rating
 * @property Collection<StatusConditionEdition> $condition_immunities
 * @property Creature $creature
 * @property Collection<DamageType> $damage_immunities
 * @property Collection<DamageType> $damage_resistances
 * @property ?CreatureSense $darkvision
 * @property GameEdition $game_edition
 * @property bool $has_fixed_proficiency_bonus
 * @property ?int $height
 * @property ?DiceFormula $height_modifier
 * @property ?CreatureHitPoints $hitPoints
 * @property bool $is_playable
 * @property MovementSpeedGroup $movementSpeeds
 * @property ?int $proficiency_bonus
 * @property Collection<Reference> $references
 * @property Collection<CreatureSense> $senses
 * @property ?Collection<CreatureSizeUnit> $sizes
 * @property ?Collection<Tag> $tags
 * @property CreatureTypeEdition $type
 * @property ?int $weight
 * @property ?DiceFormula $weight_modifier
 *
 * @property AbilityScore $str
 * @property AbilityScore $dex
 * @property AbilityScore $con
 * @property AbilityScore $int
 * @property AbilityScore $wis
 * @property AbilityScore $cha
 */
class CreatureEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();

        $this->condition_immunities = new Collection();
        $this->damage_immunities = new Collection();
        $this->damage_resistances = new Collection();
        $this->sizes = new Collection();
    }

    protected function casts(): array
    {
        return [
            'condition_immunities' => 'collection',
            'damage_immunities' => 'collection',
            'damage_resistances' => 'collection',
            'game_edition' => GameEdition::class,
            'has_fixed_proficiency_bonus' => 'boolean',
            'height_modifier' => DiceFormula::class,
            'is_playable' => 'boolean',
            'sizes' => 'collection',
            'weight_modifier' => DiceFormula::class,
        ];
    }

    public function abilityScoreModifiers(): MorphOne
    {
        return $this->morphOne(AbilityScoreModifierGroup::class, 'parent');
    }

    public function ages(): HasMany
    {
        return $this->hasMany(CreatureAge::class);
    }

    public function armorClass(): BelongsTo
    {
        return $this->hasOne(ArmorClass::class, 'armor_class_id');
    }

    public function creature(): BelongsTo
    {
        return $this->belongsTo(Creature::class);
    }

    public function darkvision(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->senses->find('type', SenseType::DARKVISION)
        );
    }

    public function hasResistance(DamageType $type): bool
    {
        return $this->damage_resistances->contains($type->value);
    }

    public function hitPoints(): BelongsTo
    {
        return $this->belongsTo(CreatureHitPoints::class, 'creature_hit_points_id');
    }

    public function isImmuneTo(StatusConditionEdition | DamageType $type): bool
    {
        return $this->condition_immunities->contains($type) || $this->damage_immunities->contains($type);
    }

    public function movementSpeeds(): MorphOne
    {
        return $this->morphOne(MovementSpeedGroup::class, 'parent');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function senses(): HasMany
    {
        return $this->hasMany(CreatureSense::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CreatureTypeEdition::class, 'creature_type_edition_id');
    }

    public function toArrayFull(): array
    {
        $output = [];

        if (!empty($this->abilityScoreModifiers)) {
            $output['abilityModifier'][] = $this->abilityScoreModifiers->toArray();
        }

        foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $ability) {
            if (!empty($this->{$ability})) {
                $output['ability'][$ability] = $this->{$ability}->toArray();
            }
        }

        foreach ($this->ages as $age) {
            $output['age'][$age->type->toString()] = $age->value;
        }
        $output['challengeRating'] = $this->challenge_rating;
        $output['conditionImmune'] = $this->condition_immunities;
        $output['hp'] = $this->hitPoints?->toArray($this->renderMode);
        $output['immune'] = $this->damage_immunities;
        $output['isPlayable'] = $this->is_playable;
        $output['proficiencyBonus'] = $this->proficiency_bonus;
        $output['resist'] = $this->damage_resistances;

        foreach ($this->senses as $sense) {
            $output['senses'][] = $sense->toArray();
        }

        $output['speed'] = $this->movementSpeeds?->toArray() ?? [];
        // TODO: come back to this.
        //$output['tags'] = ModelCollection::make($this->tags)->toArray();

        if (!empty($this->references)) {
            $output['references'] = ModelCollection::make($this->references)->toArray();
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'size' => $this->sizes->toArray(),
            'creatureId' => $this->creature->id,
            'gameEdition' => $this->game_edition->toStringShort(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        // Assume it's the most recent edition, and if the reference given below is a source from 5e (2014) we'll
        // change the edition.
        $item->game_edition = GameEdition::FIFTH_REVISED;
        $item->creature()->associate($parent);

        $item->save();

        // Creature size.
        if (!empty($value['size'])) {
            // There's a weird case in the 5e.tools data for the "Verdan" in Acquisitions Incorporated where the size
            // is listed as "V". Verdan can be S or M size.
            if ($value['size'] === 'V' || $value['size'][0] === 'V') {
                $value['size'] = ['S', 'M'];
            }

            if (!is_array($value['size'])) {
                $value['size'] = [$value['size']];
            }
            foreach ($value['size'] as $size) {
                $sizeUnit = CreatureSizeUnit::tryFromString($size);
                $item->sizes()->save($sizeUnit);
            }
        }

        /**
         * Ability modifiers.
         */
        if (!empty($value['ability'])) {
            $modifierGroup = AbilityScoreModifierGroup::fromInternalJson($value['ability'], $item);
            $item->abilityScoreModifiers()->save($modifierGroup);
        }

        // Reference.
        if (!empty($value['source'])) {
            $reference = Reference::from5eJson([
                'source' => $value['source'],
                'page' => $value['page'] ?? null,
            ], $item);
            // Use the game edition of the sourcebook.
            $item->game_edition = GameEdition::tryFromString($reference->edition->source->game_edition);
        }

        // Movement speeds.
        if (!empty($value['speed'])) {
            $movementSpeedGroup = MovementSpeedGroup::fromInternalJson($value['speed'], $item);
            $item->movementSpeeds()->save($movementSpeedGroup);
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        // Do we already have an edition?
        /** @var Creature $parent */
        $item = $parent->editions->first() ??  new static();
        $item->creature()->associate($parent);
        $item->save();

        /**
         * Game Edition.
         */
        if (!empty($value['source'])) {
            // Check we don't already have this reference.
            if (!$item->references->contains('source.slug', $value['source'])) {
                $reference = Reference::from5eJson([
                    'source' => $value['source'],
                    'page' => $value['page'] ?? null,
                ], $item);
                $item->references()->save($reference);
            }

            // Try to infer the game edition from the sourcebook.
            $item->game_edition = GameEdition::tryFromString($reference->edition->source->game_edition);
        }
        // In case we couldn't set an edition, assume 5th edition.
        if (empty($item->game_edition)) {
            $item->game_edition = GameEdition::FIFTH_REVISED;
        }

        /**
         * Challenge Rating.
         */
        if (!empty($value['cr'])) {
            $item->challenge_rating = $value['cr'];
        }

        /**
         * Sizes.
         */
        // In case "size" is not an array, turn it into one.
        if (!empty($value['size']) && !is_array($value['size'])) {
            $value['size'] = [$value['size']];
        }

        foreach ($value['size'] ?? [] as $size) {
            $sizeUnit = CreatureSizeUnit::tryFromString($size);
            if (!$item->sizes->contains($sizeUnit)) {
                $item->sizes->add($sizeUnit);
            }
        }

        /**
         * Creature type.
         */
        if (!empty($value['type']) && empty($item->type)) {
            try {
                $type = CreatureMajorType::query()->where('slug', $value['type'])->firstOrFail();
                $item->type()->associate($type);
            } catch (ModelNotFoundException $e) {
                die("Could not find CreatureMajorType: {$value['type']}\n");
            }
        }

        /**
         * Ability scores.
         */
        foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $ability) {
            if (!empty($value[$ability]) && empty($item->{$ability})) {
                $abilityScore = AbilityScore::fromNumber($value[$ability], $ability, $item);
                $item->{$ability}()->associate($abilityScore);
            }
        }

        /**
         * Armor class.
         */
        if (!empty($value['ac']) && empty($item->armorClass)) {
            // Remove the dexterity modifier from the armor class.
            $modifier = $item->dex->modifier;
            $ac = ArmorClass::from5eJson($value['ac'] - $modifier, $item);
            $item->armorClass()->associate($ac);
        }

        /**
         * Hit points.
         */
        if (!empty($value['hp']) && empty($item->hitPoints)) {
            $hpItem = CreatureHitPoints::from5eJson($value['hp'], $item);
            $item->hitPoints()->associate($hpItem);
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

                $condition = StatusCondition::query()->where('slug', $conditionItem)->firstOrFail();
                $conditionEdition = $condition->editions->where('game_edition', $item->game_edition)->firstOrFail();

                // Check if already immune.
                if (!$item->isImmuneTo($conditionEdition)) {
                    $item->condition_immunities->add($conditionEdition);
                }
            } catch (ModelNotFoundException $e) {
                print("[WARNING] Could not find StatusCondition: {$conditionItem}\n");
            } catch (ItemNotFoundException $e) {
                print sprintf(
                    "[WARNING] Could not find %s StatusConditionEdition for %s\n",
                    $item->game_edition->toStringShort(),
                    $conditionItem
                );
            }
        }
        // TODO: come back to this.
//        foreach ($value['immune'] ?? [] as $damageTypeItem) {
//            $damageType = DamageType::tryFromString($damageTypeItem);
//
//            // Check if already immune.
//            if (!$item->isImmuneTo($damageTypeItem)) {
//                $item->damage_immunities->add($damageType);
//            }
//        }
//        foreach ($value['resist'] ?? [] as $damageTypeItem) {
//            $damageType = DamageType::tryFromString($damageTypeItem);
//
//            // Check if we already have this resistance.
//            if (!$item->hasResistance($damageType)) {
//                $item->damage_resistances->add($damageType);
//            }
//        }

        /**
         * Senses.
         */
        foreach ($value['senses'] ?? [] as $senseItem) {
            $sense = CreatureSense::from5eJson($senseItem, $item);

            try {
                $item->senses()->save($sense);
            } catch  (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for sense {$senseItem}\n";
            }
        }
        // Sometimes the key is just in the $value array instead of a "senses" array.
        if (!empty($value['darkvision']) && empty($item->darkvision)) {
            $darkvision = CreatureSense::from5eJson([
                'type' => 'darkvision',
                'value' => (int) $value['darkvision']
            ], $item);
            $item->senses()->save($darkvision);
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
         * Ages.
         */
        foreach ($value['age'] ?? [] as $ageType => $ageItem) {
            try {
                $age = CreatureAge::from5eJson([
                    'type' => $ageType,
                    'value' => $ageItem
                ], $item);

                $item->ages()->save($age);
            } catch (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for age {$ageType}\n";
            }
        }

        $item->save();
        return $item;
    }
}
