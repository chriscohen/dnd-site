<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<PrerequisiteSpecies> $species
 * @property PrerequisiteGroup $group
 */
class PrerequisiteSpeciesGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function group(): BelongsTo
    {
        return $this->belongsTo(PrerequisiteGroup::class);
    }

    public function species(): HasMany
    {
        return $this->hasMany(PrerequisiteSpecies::class);
    }

    public function toArrayFull(): array
    {
        return [

        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group->id,
            'species' => ModelCollection::make($this->species)->toArray(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * @param array{
     *     array{name: string}
     * } $value
     */
    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);

        // In 5e.tools JSON it's called "race" but we're going to use the term "species".
        $children = $values['species'] ?? $values['race'] ?? [];

        foreach ($children as $child) {
            PrerequisiteSpecies::fromInternalJson($child['name'], $item);
        }

        $item->save();
        return $item;
    }
}
