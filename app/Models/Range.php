<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Distance;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $is_self
 * @property bool $is_touch
 * @property int $number
 * @property int $per_level
 * @property int $per_level_increment
 *   How many levels to go up by. For example, a value of 2 would be "per 2 levels".
 * @property Distance $unit
 */
class Range extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'is_self' => 'boolean',
        'is_touch' => 'boolean',
        'unit' => Distance::class,
    ];

    public function format(int $number, Distance $distance): string
    {
        return $number . ' ' . ($number == 1 ? $distance->plural() : $distance->toString());
    }

    public function formatLevelIncrement(int $increment): string
    {
        return $increment == 1 ? 'per level' : 'per ' . $increment . ' levels';
    }

    public function isUnlimited(): bool
    {
        return $this->number !== null && $this->number < 0;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'is_self' => $this->is_self,
            'is_touch' => $this->is_touch,
            'number' => $this?->number ?? null,
            'per_level' => $this?->per_level ?? null,
            'unit' => $this?->unit?->toString() ?? null,
        ];
    }

    public function toString(): string
    {
        if ($this->number < 0) {
            return 'Unlimited';
        } elseif ($this->is_self) {
            return 'Self';
        } elseif ($this->is_touch) {
            return 'Touch';
        }

        $output = $this->format($this->number, $this->unit);

        if ($this->per_level !== null) {
            $output .= ' + ' . $this->format($this->per_level, $this->unit) . ' ' .
                $this->formatLevelIncrement($this->per_level_increment);
        }

        return $output;
    }
}
