<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DamageType;
use App\Enums\PerLevelMode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property DamageType $damage_type
 * @property int $die_faces
 * @property ?int $die_quantity
 * @property ?int $die_quantity_maximum
 * @property ModelInterface $entity
 * @property int $modifier
 * @property PerLevelMode $per_level_mode
 */
class DamageInstance extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'damage_type' => DamageType::class,
        'per_level_mode' => PerLevelMode::class,
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function formatDice(): string
    {
        return empty($this->die_quantity) ? '' . $this->die_faces : $this->die_quantity . 'd' . $this->die_faces;
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'damage_type' => $this->damage_type,
            'die_faces' => $this->die_faces,
            'die_quantity' => $this->die_quantity,
            'die_quantity_maximum' => $this->die_quantity_maximum,
            'entity_id' => $this->entity->id,
            'modifier' => $this->modifier,
            'per_level_mode' => $this->per_level_mode,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'string' => $this->toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * For example, 1d8 + 5 acid damage/caster level (maximum 15d8).
     *
     * @return string
     */
    public function toString(): string
    {
        $output = $this->formatDice();

        if (!empty($this->modifier)) {
            $output .= $this->modifier > 0 ?
                ' + ' . $this->modifier :
                ' - ' . $this->modifier;
        }

        $output .= ' ' . $this->damage_type->toString() . ' damage';

        $output .= match ($this->per_level_mode) {
            PerLevelMode::NONE => $output,
            PerLevelMode::PER_LEVEL, PerLevelMode::PER_CASTER_LEVEL =>
                $output . '/' . $this->per_level_mode->toString(),
        };

        if (!empty($this->die_quantity_maximum)) {
            $output .= ' (maximum ' . $this->die_quantity_maximum . 'd' . $this->die_faces . ')';
        }

        return $output;
    }
}
