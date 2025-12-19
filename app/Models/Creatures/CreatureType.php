<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Groups a collection of fields used to describe the type of creature.
 *
 * Each edition did this slightly differently.
 *
 * @property Uuid $id
 * @property string $slug
 *
 * @property Collection<CreatureTypeEdition> $editions
 * @property string $name
 */
class CreatureType extends AbstractModel
{
    use HasUuids;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureTypeEdition::class, 'creature_type_id');
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        throw new \Exception('Not implemented');
    }
}
