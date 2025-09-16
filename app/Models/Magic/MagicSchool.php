<?php

declare(strict_types=1);

namespace App\Models\Magic;

use App\Models\AbstractModel;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 *
 * @property Media $image
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function toArrayLong(): array
    {
        return [
            'image' => $this->image->toArray($this->renderMode, $this->excluded),
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
