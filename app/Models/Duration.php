<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PerLevelMode;
use App\Enums\TimeUnit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?int $per_level
 * @property ?PerLevelMode $per_level_mode
 * @property TimeUnit $unit
 * @property ?int $value
 */
class Duration extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'per_level_mode' => PerLevelMode::class,
        'unit' => TimeUnit::class,
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'per_level' => $this->per_level,
            'per_level_mode' => $this->per_level_mode?->toString(),
            'unit' => $this->unit->toString(),
            'value' => $this->value,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'string' => $this->toString()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function toString(): string
    {
        if (empty($this->value) && empty($this->per_level)) {
            return $this->unit->toString();
        }

        if (empty($this->value) && !empty($this->per_level)) {
            return $this->unit->format($this->per_level) . ' ' . $this->per_level_mode->toString();
        }

        $output = $this->unit->format($this->value);

        if (!empty($this->per_level_mode)) {
            $output .= ' + ' . $this->unit->format($this->per_level) . ' ' . $this->per_level_mode->toString();
        }

        return $output;
    }
}
