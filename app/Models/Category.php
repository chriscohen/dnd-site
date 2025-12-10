<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use App\Traits\IdentifiesModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Media $image
 * @property Uuid $image_id
 * @property string $entity_type
 * @property string $name
 * @property ?Category $parent
 * @property Uuid $parent_id
 *
 */
class Category extends AbstractModel
{
    use IdentifiesModel;
    use HasUuids;

    public $timestamps = false;

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function toArrayFull(): array
    {
        return [
            'image' => $this->image?->toArray($this->renderMode),
            'parent' => $this->parent?->toArray($this->renderMode, []),
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

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
