<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Prerequisite $prerequisite
 * @property string $value
 */
class PrerequisiteValue extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Prerequisite::class);
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'value' => $this->value,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
