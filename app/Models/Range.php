<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Distance;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $isFromCaster
 * @property bool $isSelf
 * @property bool $isTouch
 * @property int $number
 * @property int $perLevel
 * @property int $perLevelIncrement
 *   How many levels to go up by. For example, a value of 2 would be "per 2 levels".
 * @property Distance $unit
 */
class Range extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'isFromCaster' => 'boolean',
        'isSelf' => 'boolean',
        'isTouch' => 'boolean',
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

    public function toArrayFull(): array
    {
        return [
            'isFromCaster' => $this->isFromCaster,
            'isSelf' => $this->isSelf,
            'isTouch' => $this->isTouch,
            'number' => $this?->number ?? null,
            'perLevel' => $this?->perLevel ?? null,
            'unit' => $this?->unit?->toString() ?? null,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'string' => $this->toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function toString(): string
    {
        if ($this->isFromCaster) {
            return 'From caster';
        } elseif ($this->isTouch) {
            return 'Touch';
        } elseif ($this->isSelf) {
            return 'Self';
        } elseif (empty($this->number) || $this->number < 0) {
            return 'Unlimited';
        }

        $output = $this->format($this->number, $this->unit);

        if ($this->perLevel !== null) {
            $output .= ' + ' . $this->format($this->perLevel, $this->unit) . ' ' .
                $this->formatLevelIncrement($this->perLevelIncrement);
        }

        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
