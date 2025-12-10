<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PerLevelMode;
use App\Enums\TargetType;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $description
 * @property bool $inArea
 * @property ?int $perLevel
 * @property ?PerLevelMode $perLevelMode
 * @property int $quantity
 * @property SpellEdition
 * @property TargetType $type
 */
class Target extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'inArea' => 'boolean',
        'perLevelMode' => PerLevelMode::class,
        'type' => TargetType::class,
    ];

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class, 'spellEditionId');
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'inArea' => $this->inArea,
            'perLevel' => $this->perLevel,
            'perLevelMode' => $this->perLevelMode?->toString(),
            'quantity' => $this->quantity,
            'type' => $this->type->toString(),
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

    public function toString(): string
    {
        if ($this->type == TargetType::SELF) {
            return 'self';
        }

        $plural = $this->quantity > 1;
        $output = $this->quantity . ' ';

        if (!empty($this->description)) {
            $output .= $this->description;
        } else {
            $output .= $this->type->toString() . ($plural ? 's' : '');
        }

        if (!empty($this->perLevel) && !empty($this->perLevelMode)) {
            $output .= ' + ' . $this->perLevel . ' ' . $this->perLevelMode->toString();
        }

        if ($this->inArea) {
            $output .= ' in area';
        }

        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
