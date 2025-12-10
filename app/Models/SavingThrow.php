<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SavingThrows\SavingThrowMultiplier;
use App\Enums\SavingThrows\SavingThrowType;
use App\Models\Spells\SpellEdition;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?StatusConditionEdition $failStatus
 * @property ?Uuid $failStatusId
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
        return $this->belongsTo(StatusConditionEdition::class, 'failStatusId');
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class, 'spellEditionId');
    }

    public function succeedStatus(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class, 'succeedStatusId');
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
            'failStatus' => $this->failStatus?->toArray($this->renderMode),
            'multiplier' => $this->multiplier?->toString(),
            'succeedStatus' => $this->succeedStatus?->toArray($this->renderMode),
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
