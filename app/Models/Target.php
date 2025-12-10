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
 * @property bool $in_area
 * @property ?int $per_level
 * @property ?PerLevelMode $per_level_mode
 * @property int $quantity
 * @property SpellEdition
 * @property TargetType $type
 */
class Target extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'in_area' => 'boolean',
        'per_level_mode' => PerLevelMode::class,
        'type' => TargetType::class,
    ];

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class, 'spell_edition_id');
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'in_area' => $this->in_area,
            'per_level' => $this->per_level,
            'per_level_mode' => $this->per_level_mode?->toString(),
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

        if (!empty($this->per_level) && !empty($this->per_level_mode)) {
            $output .= ' + ' . $this->per_level . ' ' . $this->per_level_mode->toString();
        }

        if ($this->in_area) {
            $output .= ' in area';
        }

        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
