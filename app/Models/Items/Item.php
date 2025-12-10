<?php

declare(strict_types=1);

namespace App\Models\Items;

use App\Models\AbstractModel;
use App\Models\Category;
use App\Models\Media;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Collection<Category> $categories
 * @property Collection<ItemEdition> $editions
 * @property ?Media $image
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

    public function defaultEdition(): ItemEdition
    {
        return $this->editions->where('is_default', true)->firstOrFail();
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ItemEdition::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function toArrayFull(): array
    {
        return [
            'categories' => ModelCollection::make($this->categories)->toArray($this->renderMode),
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
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
            'image' => $this->image?->toArray($this->renderMode),
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
