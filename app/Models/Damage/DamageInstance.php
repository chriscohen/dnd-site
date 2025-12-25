<?php

declare(strict_types=1);

namespace App\Models\Damage;

use App\Enums\Damage\DamageType;
use App\Enums\PerLevelMode;
use App\Models\AbstractModel;
use App\Models\Conditions\ConditionEdition;
use App\Models\Conditions\StatusConditionEdition;
use App\Models\Dice\DiceFormula;
use App\Models\Effects\Effect;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?StatusConditionEdition $conditionEdition
 * @property ?DamageType $damage_type
 * @property Effect $effect
 * @property DiceFormula $formula
 * @property ?int $modifier
 * @property int $quantity
 */
class DamageInstance extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'damage_type' => DamageType::class,
        'formula' => DiceFormula::class,
    ];

    public function effect(): BelongsTo
    {
        return $this->belongsTo(Effect::class, 'effect_id');
    }

    public function formatDice(): string
    {
        return $this->formula->toString(withSpaces: true);
    }

    public function statusConditionEdition(): BelongsTo
    {
        return $this->belongsTo(ConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'damage_type' => $this->damage_type,
            'dice_count' => $this->die_quantity,
            'dice_faces' => $this->die_faces,
            'effect_id' => $this->effect->id,
            'modifier' => $this->modifier,
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
    public function toString(?bool $withSpaces = true): string
    {
        return $this->formula->toString(withSpaces: $withSpaces);
    }

    /**
     * @param array|string|int $value
     * @param ?Effect $parent
     */
    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->effect()->associate($parent);

        $formula = new DiceFormula();
        $formula->diceCount = $value['dice_count'];
        $formula->diceFaces = $value['dice_faces'];
        $formula->modifier = $value['modifier'] ?? 0;
        $item->formula = $formula;

        if (!empty($value['damage_type'])) {
            $item->damage_type = DamageType::tryFromString($value['damage_type']);
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
