<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Models\ModelInterface;
use App\Models\Species\Species;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property PrerequisiteSpeciesGroup $group
 * @property ?string $name
 * @property ?Species $species
 */
class PrerequisiteSpecies extends AbstractPrerequisite
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'prerequisite_species';

    public function group(): BelongsTo
    {
        return $this->belongsTo(PrerequisiteSpeciesGroup::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            // Species could be a reference to a Species entity, or just a string.
            'species' => $this->name ?? $this->species->toArrayShort(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);

        if (empty($value['displayEntry'])) {
            // If there's no "displayEntry" property, we are referencing a Species entity.
            $species = Species::query()->where('id', $value)->firstOrFail();
            $item->species()->associate($species);
        } else {
            // We are describing a species or group by string.
            $item->name = $value['displayEntry'];
        }

        $item->save();
        return $item;
    }

    public static function fromFeJson(array $value, ModelInterface $parent = null): ModelInterface
    {
        return static::fromInternalJson($value, $parent);
    }
}
