<?php

declare(strict_types=1);

namespace App\Models\Items;

use App\Models\AbstractModel;
use App\Models\Category;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Uuid $category_id
 * @property Category $category
 * @property Collection<ItemEdition> $editions
 * @property string $name
 */
class Item extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ItemEdition::class);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,

            'category' => $this->category->toArray(),
            'editions' => ModelCollection::make($this->editions)->toArray(),
        ];
    }
}
