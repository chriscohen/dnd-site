<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DistanceUnit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $is_from_caster
 * @property bool $is_self
 * @property bool $is_touch
 * @property int $number
 * @property int $per_level
 * @property int $per_level_increment
 *   How many levels to go up by. For example, a value of 2 would be "per 2 levels".
 * @property DistanceUnit $unit
 */
class Range extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'is_from_caster' => 'boolean',
        'is_self' => 'boolean',
        'is_touch' => 'boolean',
        'unit' => DistanceUnit::class,
    ];

    public function format(int $number, DistanceUnit $distance): string
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
            'is_from_caster' => $this->is_from_caster,
            'is_self' => $this->is_self,
            'is_touch' => $this->is_touch,
            'number' => $this?->number ?? null,
            'per_level' => $this?->per_level ?? null,
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
        if ($this->is_from_caster) {
            return 'From caster';
        } elseif ($this->is_touch) {
            return 'Touch';
        } elseif ($this->is_self) {
            return 'Self';
        } elseif (empty($this->number) || $this->number < 0) {
            return 'Unlimited';
        }

        $output = $this->format($this->number, $this->unit);

        if ($this->per_level !== null) {
            $output .= ' + ' . $this->format($this->per_level, $this->unit) . ' ' .
                $this->formatLevelIncrement($this->per_level_increment);
        }

        return $output;
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->is_from_caster = $value['isFromCaster'] ?? false;
        $item->is_self = $value['isSelf'] ?? false;
        $item->is_touch = $value['isTouch'] ?? false;
        $item->per_level = $value['perLevel'] ?? null;
        $item->per_level_increment = $value['perLevelIncrement'] ?? 1;

        $item->number = $value['number'] ?? null;
        $item->unit = DistanceUnit::tryFromString($value['unit'] ?? 'ft');

        $item->save();
        return $item;
    }

    /**
     * @param  array|string  $value
     */
    public static function from5eJson(array|string $value, ModelInterface $parent = null): ModelInterface
    {
        $item = new static();

        if ($value['distance']['type'] === 'touch') {
            $item->is_touch = true;
        } else {
            $item->unit = DistanceUnit::tryFromString($value['distance']['type']);
            $item->number = $value['distance']['amount'];
        }

        $item->save();
        return $item;
    }
}
