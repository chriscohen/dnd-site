<?php

declare(strict_types=1);

namespace App\Models\Magic;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 *
 * @property ?string $description
 * @property Media $image
 * @property ?MagicSchool $parent
 * @property string $parent_id
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'parent_id');
    }

    public function toArrayLong(): array
    {
        return [
            'description' => $this->description,
            'image' => $this->image?->toArray($this->renderMode, $this->excluded),
            'parent' => $this->parent?->toArray(),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
