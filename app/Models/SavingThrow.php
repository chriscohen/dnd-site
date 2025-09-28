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
 * @property ?StatusCondition $failStatus
 * @property ?Uuid $fail_status_id
 * @property ?SavingThrowMultiplier $multiplier
 * @property SpellEdition $spellEdition
 * @property SavingThrowType $type
 */
class SavingThrow extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'fail_status' => StatusConditionEdition::class,
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
        ];
    }
}
