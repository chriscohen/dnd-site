<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\IdentifiesModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property string $entity_type
 * @property string $name
 * @property ?Category $parent
 * @property Uuid $parent_id
 *
 */
class Category extends AbstractModel implements HasMedia
{
    use IdentifiesModel;
    use InteractsWithMedia;
    use HasUuids;

    public $timestamps = false;
    protected $primaryKey = 'id';

    public function image(): HasOne
    {
        return $this->hasOne(Media::class, 'model_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
