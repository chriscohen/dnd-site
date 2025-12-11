<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SavingThrows\SavingThrowMultiplier;
use App\Enums\SavingThrows\SavingThrowType;
use App\Models\Spells\SpellEdition;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?StatusConditionEdition $failStatus
 * @property ?Uuid $fail_status_id
 * @property ?SavingThrowMultiplier $multiplier
 * @property SpellEdition $spellEdition
 * @property ?StatusConditionEdition $succeedStatus
 * @property SavingThrowType $type
 */
class SavingThrow extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'multiplier' => SavingThrowMultiplier::class,
        'type' => SavingThrowType::class,
    ];

    public function failStatus(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class, 'fail_status_id');
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class, 'spell_edition_id');
    }

    public function succeedStatus(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class, 'succeed_status_id');
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'type' => $this->type->toString()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'fail_status' => $this->failStatus?->toArray($this->renderMode),
            'multiplier' => $this->multiplier?->toString(),
            'succeed_status' => $this->succeedStatus?->toArray($this->renderMode),
        ];
    }

    /**
     * @param array|string|int $value
     * @param SpellEdition $parent
     */
    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->spellEdition()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->type = SavingThrowType::tryFromString($value['type']);

        if (!empty($data['multiplier'])) {
            $item->multiplier = SavingThrowMultiplier::tryFromString($value['multiplier']);
        }

        // TODO: revisit this
//        if (!empty($value['failStatus'])) {
//            $condition = StatusCondition::query()
//                ->where('slug', $value['failStatus'])
//                ->first();
//
//            if (empty($condition)) {
//                throw new \Exception("Invalid fail_status: " . $value['failStatus']);
//            }
//
//            $x = $parent->game_edition;
//            $item->failStatus()->associate(
//                $condition->editions->where('game_edition', $parent->game_edition)->firstOrFail()
//            );
//        }

        $item->save();
        return $item;
    }
}
