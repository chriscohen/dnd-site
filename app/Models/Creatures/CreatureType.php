<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
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

    public function toArrayLong(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode, $this->excluded),
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
}
