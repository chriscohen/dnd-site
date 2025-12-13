<?php

declare(strict_types=1);

namespace App\Models\Feats;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<FeatureEdition> $editions
 */
class Feature extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function editions(): HasMany
    {
        return $this->hasMany(FeatureEdition::class);
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
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->save();

        foreach ($value['editions'] ?? [] as $editionData) {
            $edition = FeatureEdition::fromInternalJson($editionData, $item);
            $item->editions()->save($edition);
        }

        $item->save();
        return $item;
    }
}
