<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Attribute;
use App\Enums\DamageType;
use App\Enums\PerLevelMode;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Attribute $attributeModifier
 * @property ?int $attributeModifierQuantity
 * @property DamageType $damageType
 * @property ?int $dieFaces
 * @property ?int $fixedDamage
 * @property ?int $fixedDamageMaximum
 * @property ?int $dieQuantity
 * @property ?int $dieQuantityMaximum
 * @property ModelInterface $entity
 * @property ?int $modifier
 * @property ?int $perLevelDieFaces
 * @property ?int $perLevelDieQuantity
 * @property ?int $perLevelFixedDamage
 * @property ?PerLevelMode $perLeveLMode
 * @property int $quantity
 * @property ?StatusConditionEdition $statusConditionEdition
 */
class DamageInstance extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'damageDype' => DamageType::class,
        'perLevelMode' => PerLevelMode::class,
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function formatDice(): string
    {
        return empty($this->dieQuantity) ? '' . $this->dieFaces : $this->dieQuantity . 'd' . $this->dieFaces;
    }

    public function statusConditionEdition(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'damageType' => $this->damageType,
            'dieFaces' => $this->dieFaces,
            'dieQuantity' => $this->dieQuantity,
            'dieQuantityMaximum' => $this->dieQuantityMaximum,
            'fixedDamage' => $this->fixedDamage,
            'fixedDamageMaximum' => $this->fixedDamageMaximum,
            'entityId' => $this->entity->id,
            'modifier' => $this->modifier,
            'perLevelDieFaces' => $this->perLevelDieFaces,
            'perLevelDieQuantity' => $this->perLevelDieQuantity,
            'perLevelFixedDamage' => $this->perLevelFixedDamage,
            'perLevelMode' => $this->perLeveLMode,
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

        $output .= ' ' . $this->damageType?->toString() . ' damage';

        $output .= match ($this->perLeveLMode) {
            PerLevelMode::NONE => $output,
            PerLevelMode::PER_LEVEL, PerLevelMode::PER_CASTER_LEVEL =>
                $output . '/' . $this->perLeveLMode->toString(),
        };

        if (!empty($this->dieQuantityMaximum)) {
            $output .= ' (maximum ' . $this->dieQuantityMaximum . 'd' . $this->dieFaces . ')';
        }

        // Add overall quantity.
        if ($this->quantity > 1) {
            $output = $this->quantity . ' x ' . $output;
        }

        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
