<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PerLevelMode;
use App\Enums\Units\DurationEndCondition;
use App\Enums\Units\TimeUnit;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $concentration
 * @property ?Collection<DurationEndCondition> $ends
 * @property ?int $per_level
 * @property ?PerLevelMode $per_level_mode
 * @property TimeUnit $unit
 * @property ?int $value
 */
class Duration extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts()
    {
        return [
            'concentration' => 'boolean',
            'ends' => AsEnumCollection::of(DurationEndCondition::class),
            'per_level_mode' => PerLevelMode::class,
            'unit' => TimeUnit::class,
        ];
    }

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

        if (empty($this->value) && !empty($this->per_level) && !empty($this->per_level_mode)) {
            return $this->unit->format($this->per_level) . ' ' . $this->per_level_mode->toString();
        }

        $output = $this->unit->format($this->value);

        if (!empty($this->per_level_mode)) {
            $output .= ' + ' . $this->unit->format($this->per_level) . ' ' . $this->per_level_mode->toString();
        }

        return $output;
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);
        $item->unit = TimeUnit::tryFromString($value['unit']);
        $item->value = $value['value'] ?? null;
        $item->per_level = $value['perLevel'] ?? null;
        $item->per_level_mode = !empty($value['perLevelMode']) ?
            PerLevelMode::tryFromString($value['perLevelMode']) :
            null;

        $item->save();
        return $item;
    }

    /**
     * @param  array|string|int  $value
     */
    public static function from5eJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);

        if (empty($value['type']) || $value['type'] === 'timed') {
            $item->unit = !empty($value['duration']['type']) ?
                TimeUnit::tryFromString($value['duration']['type']) :
                null;
            if ($item->unit?->hasNumber()) {
                $item->value = $value['duration']['amount'];
            }
        } else {
            $item->unit = TimeUnit::tryFromString($value['type']);
        }

        if (!empty($value['concentration']) && $value['concentration'] === true) {
            $item->concentration = true;
        }

        foreach ($value['ends'] ?? [] as $end) {
            if ($item->ends === null) {
                $item->ends = collect();
            }
            $endCondition = DurationEndCondition::tryFromString($end);
            $item->ends->add($endCondition);
        }

        $item->save();
        return $item;
    }
}
