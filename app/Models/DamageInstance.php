<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Attribute;
use App\Enums\DamageType;
use App\Enums\GameEdition;
use App\Enums\PerLevelMode;
use App\Models\Spells\SpellEdition;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Attribute $attribute_modifier
 * @property ?int $attribute_modifier_quantity
 * @property DamageType $damage_type
 * @property ?int $die_faces
 * @property ?int $fixed_damage
 * @property ?int $fixed_damage_maximum
 * @property ?int $die_quantity
 * @property ?int $die_quantity_maximum
 * @property ModelInterface $entity
 * @property ?int $modifier
 * @property ?int $per_level_die_faces
 * @property ?int $per_level_die_quantity
 * @property ?int $per_level_fixed_damage
 * @property ?PerLevelMode $per_level_mode
 * @property int $quantity
 * @property ?StatusConditionEdition $statusConditionEdition
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

    public function statusConditionEdition(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'damage_type' => $this->damage_type,
            'die_faces' => $this->die_faces,
            'die_quantity' => $this->die_quantity,
            'die_quantity_maximum' => $this->die_quantity_maximum,
            'fixed_damage' => $this->fixed_damage,
            'fixed_damage_maximum' => $this->fixed_damage_maximum,
            'entity_id' => $this->entity->id,
            'modifier' => $this->modifier,
            'per_level_die_faces' => $this->per_level_die_faces,
            'per_level_die_quantity' => $this->per_level_die_quantity,
            'per_level_fixed_damage' => $this->per_level_fixed_damage,
            'per_level_mode' => $this->per_level_mode,
            'quantity' => $this->quantity,
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

        $output .= ' ' . $this->damage_type?->toString() . ' damage';

        $output .= match ($this->per_level_mode) {
            default => '',
            PerLevelMode::NONE => $output,
            PerLevelMode::PER_LEVEL, PerLevelMode::PER_CASTER_LEVEL =>
                $output . '/' . $this->per_level_mode->toString(),
        };

        if (!empty($this->die_quantity_maximum)) {
            $output .= ' (maximum ' . $this->die_quantity_maximum . 'd' . $this->die_faces . ')';
        }

        // Add overall quantity.
        if ($this->quantity > 1) {
            $output = $this->quantity . ' x ' . $output;
        }

        return $output;
    }

    /**
     * @param array|string|int $value
     * @param SpellEdition $parent
     */
    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);

        $item->die_quantity = $value['die_quantity'] ?? null;
        $item->die_quantity_maximum = $value['die_quantity_maximum'] ?? null;
        $item->die_faces = $value['die_faces'] ?? null;

        if (!empty($value['damage_type'])) {
            $item->damage_type = DamageType::tryFromString($value['damage_type']);
        }

        $item->modifier = $value['modifier'] ?? 0;

        if (!empty($value['attribute_modifier'])) {
            $item->attribute_modifier = Attribute::tryFromString($value['attribute_modifier']);
            $item->attribute_modifier_quantity = $value['attribute_modifier_quantity'] ?? 1;
        }

        // TODO: revisit this.
//        $item->per_level_mode = empty($value['per_level_mode']) ?
//            PerLevelMode::NONE :
//            PerLevelMode::tryFromString($value['per_level_mode']);
//
//        if (!empty($value['status_condition'])) {
//            $statusConditionEdition = StatusConditionEdition::query()
//                ->where('game_edition', $parent->game_edition)
//                ->value
//                ->whereHas('status_condition', function ($query) use ($value) {
//                })
//                ->firstOrFail();
//            $item->statusConditionEdition()->associate($statusConditionEdition);
//        }

        $item->save();
        return $item;
    }
}
