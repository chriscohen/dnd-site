<?php

declare(strict_types=1);

namespace App\Models\Species;

use App\Enums\GameEdition;
use App\Models\AbilityScores\AbilityScoreModifierGroup;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeedGroup;
use App\Models\Reference;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property AbilityScoreModifierGroup $abilityScoreModifiers
 * @property GameEdition $game_edition
 * @property MovementSpeedGroup $movementSpeeds
 * @property Collection<Size> $sizes
 * @property Species $species
 * @property Collection<Tag> $tags
 */
class SpeciesEdition extends AbstractModel
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

    public function size(): MorphMany
    {
        return $this->morphMany(Size::class, 'parent');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function toArrayFull(): array
    {
        return [
            'speed' => $this->movementSpeeds->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'species_id' => $this->species->id,
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

        // Size.
        if (is_array($value['size'])) {
            foreach ($value['size'] as $size) {
                $size = Size::fromInternalJson($size, $item);
                $item->sizes()->save($size);
            }
        } elseif (is_string($value['size'])) {
            $size = Size::fromInternalJson(['name' => $value['size']], $item);
            $item->sizes()->save($size);
        }

        /**
         *
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
            $item->game_edition = $reference->edition->source->game_edition;
        }

        $item->save();

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
