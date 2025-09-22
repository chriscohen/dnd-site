<?php

declare(strict_types=1);

namespace App\Models\Items;

use App\Models\AbstractModel;
use App\Models\Category;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Collection<Category> $categories
 * @property Collection<ItemEdition> $editions
 * @property string $name
 */
class Item extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'entity', 'entity_category');
    }

    public function primaryEdition(): ItemEdition
    {
        return $this->editions->where('is_primary', true)->firstOrFail();
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ItemEdition::class);
    }

    public function toArrayLong(): array
    {
        return [
            'category' => $this->category->toArray($this->renderMode, $this->excluded),
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
