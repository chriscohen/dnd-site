<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\Items\ItemEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $description
 * @property bool $isConsumed
 * @property bool $isFocus
 * @property bool $isPlural
 * @property Uuid $itemEditionId
 * @property ItemEdition $itemEdition
 * @property ?int $minimum_value
 * @property ?int $quantity
 * @property ?string $quantityText
 * @property Uuid $spellEditionId
 * @property SpellEdition $spellEdition
 */
class SpellMaterialComponent extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'isConsumed' => 'boolean',
        'isFocus' => 'boolean',
        'isPlural' => 'boolean',
    ];

    public function getItemData(): array
    {
        $item = $this->item();

        return [
            $item->id,
            $item->name,
            $item->slug,
        ];
    }

    public function itemEdition(): BelongsTo
    {
        return $this->belongsTo(ItemEdition::class);
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'description' => $this->description,
            'isConsumed' => $this->isConsumed,
            'isFocus' => $this->isFocus,
            'isPlural' => $this->isPlural,
            'minimumValue' => empty($this->minimum_value) ? null : $this->formatPrice($this->minimum_value),
            'quantity' => $this->quantity,
            'quantityText' => $this->quantityText,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'itemEditionId' => $this->itemEdition->id,
            'spellEditionId' => $this->spellEdition->id,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'string' => $this->toString(),
        ];
    }

    public function toString(): string
    {
        if (!empty($this->quantityText)) {
            return $this->quantityText;
        }

        $plural = $this->isPlural || (empty($this->quantityText) && $this->quantity > 1);
        $output = $this->quantity;
        $output .= ' ' . $this->itemEdition->item->name . ($plural ? 's' : '') . 's';
        return $output;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
