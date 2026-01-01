<?php

declare(strict_types=1);

namespace App\Models\Magic;

use App\Models\AbstractModel;
use App\Models\Media\Media;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 *
 * @property Collection<MagicSchool> $children
 * @property ?string $description
 * @property Media $image
 * @property ?MagicSchool $parent
 * @property string $parent_id
 * @property ?string $short_name
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function children(): HasMany
    {
        return $this->hasMany(MagicSchool::class, 'parent_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'parent_id');
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->short_name = $value['short_name'] ?? null;
        $item->description = $value['description'] ?? null;

        if (empty($value['parent'])) {
            // If there's no parent, it's a base school, which means there's an image.
            $media = Media::fromInternalJson([
                'filename' => '/magic-schools/' . $value['id'],
            ]);
            $item->image()->associate($media);
        } else {
            // If there's a parent, it won't have an image.
            $item->parent_id = $value['parent'];
        }

        $item->save();
        return $item;
    }
}
