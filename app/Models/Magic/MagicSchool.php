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

    public array $schema = [
        JsonRenderMode::SHORT->value => [
            'id' => 'string',
            'name' => 'string',
        ],
        JsonRenderMode::FULL->value => []
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
