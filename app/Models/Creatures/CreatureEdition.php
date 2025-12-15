<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\CreatureSizeUnit;
use App\Enums\GameEdition;
use App\Models\AbilityScores\AbilityScoreModifierGroup;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeedGroup;
use App\Models\Reference;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property AbilityScoreModifierGroup $abilityScoreModifiers
 * @property Creature $creature
 * @property GameEdition $game_edition
 * @property MovementSpeedGroup $movementSpeeds
 * @property Collection<Reference> $references
 * @property Collection<CreatureSizeUnit> $sizes
 * @property Collection<Tag> $tags
 */
class CreatureEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
    ];

    public function abilityScoreModifiers(): MorphOne
    {
        return $this->morphOne(AbilityScoreModifierGroup::class, 'parent');
    }

    public function movementSpeeds(): MorphOne
    {
        return $this->morphOne(MovementSpeedGroup::class, 'parent');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function sizes(): MorphMany
    {
        return $this->morphMany(CreatureSize::class, 'parent');
    }

    public function creature(): BelongsTo
    {
        return $this->belongsTo(Creature::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function toArrayFull(): array
    {
        $output = [];

        if (!empty($this->abilityScoreModifiers)) {
            $output['ability'][] = $this->abilityScoreModifiers->toArray();
        }

        if (!empty($this->movementSpeeds)) {
            $output['speed'] = $this->movementSpeeds->toArray();
        }

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

            if (is_array($value['size'])) {
                foreach ($value['size'] as $size) {
                    $size = CreatureSize::fromInternalJson($size, $item);
                    $item->sizes()->save($size);
                }
            } elseif (is_string($value['size'])) {
                $size = CreatureSize::fromInternalJson(['name' => $value['size']], $item);
                $item->sizes()->save($size);
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
        return static::fromInternalJson($value, $parent);
    }
}
