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
 * @property string $name
 *
 * @property Collection<CreatureMajorTypeEdition> $editions
 * @property string $plural
 */
class CreatureMajorType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureMajorTypeEdition::class, 'creature_major_type_id');
    }

    public function toArrayFull(): array
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
            'plural '=> $this->plural,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
