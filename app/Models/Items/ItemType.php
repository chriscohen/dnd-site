<?php

declare(strict_types=1);

namespace App\Models\Items;

use App\Models\AbstractModel;
use App\Models\Category;
use App\Models\Media\Media;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
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
 * @property Collection<ItemTypeEdition> $editions
 * @property ?Media $image
 * @property string $name
 */
class ItemType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'entity', 'entity_category');
    }

    public function defaultEdition(): ItemTypeEdition
    {
        return $this->editions->where('is_default', true)->firstOrFail();
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ItemTypeEdition::class);
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->slug = $value['slug'] ?? self::makeSlug($value['name']);
        $item->name = $value['name'];

        if (!empty($value['image'])) {
            $media = Media::fromInternalJson([
                'filename' => '/items/' . $value['image'],
                'collection_name' => 'spells',
            ]);
            $item->image()->associate($media);
        }

        foreach ($value['editions'] ?? [] as $editionData) {
            $edition = ItemTypeEdition::fromInternalJson($editionData, $item);
            $item->editions()->save($edition);
        }

        foreach ($value['categories'] ?? [] as $categoryData) {
            $category = Category::query()->where('slug', $categoryData)->firstOrFail();
            $item->categories()->save($category);
        }

        $item->save();
        return $item;
    }
}
