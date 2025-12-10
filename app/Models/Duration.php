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
 * @property ?int $perLevel
 * @property ?PerLevelMode $perLevelMode
 * @property TimeUnit $unit
 * @property ?int $value
 */
class Duration extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'perLevelMode' => PerLevelMode::class,
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
            'perLevel' => $this->perLevel,
            'perLevelMode' => $this->perLevelMode?->toString(),
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
        if (empty($this->value) && empty($this->perLevel)) {
            return $this->unit->toString();
        }

        if (empty($this->value) && !empty($this->perLevel) && !empty($this->perLevelMode)) {
            return $this->unit->format($this->perLevel) . ' ' . $this->perLevelMode->toString();
        }

        $output = $this->unit->format($this->value);

        if (!empty($this->perLevelMode)) {
            $output .= ' + ' . $this->unit->format($this->perLevel) . ' ' . $this->perLevelMode->toString();
        }

        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
