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

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $short = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($mode === JsonRenderMode::SHORT) {
            return $short;
        }

        return array_merge_recursive($short, [
            'image' => $this->image->toArray(),
        ]);
    }
}
