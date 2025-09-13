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

    protected array $schema = [
        JsonRenderMode::SHORT->value => [
            'id' => 'uuid',
            'slug' => 'string',
            'name' => 'string',
        ],
        JsonRenderMode::FULL->value => [
            '?image' => Media::class,
            '?parent' => Category::class,
        ],
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
