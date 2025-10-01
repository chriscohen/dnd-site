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
 * @property bool $is_consumed
 * @property bool $is_plural
 * @property Uuid $item_edition_id
 * @property ItemEdition $itemEdition
 * @property int $quantity
 * @property string $quantity_text
 * @property Uuid $spell_edition_id
 * @property SpellEdition $spellEdition
 */
class SpellMaterialComponent extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

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
            'is_consumed' => $this->is_consumed,
            'is_plural' => $this->is_plural,
            'quantity' => $this->quantity,
            'quantity_text' => $this->quantity_text,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'item_edition_id' => $this->itemEdition->id,
            'spell_edition_id' => $this->spellEdition->id,
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
        if (!empty($this->quantity_text)) {
            return $this->quantity_text;
        }

        $plural = $this->is_plural || (empty($this->quantity_text) && $this->quantity > 1);
        $output = $this->quantity;
        $output .= ' ' . $this->itemEdition->item->name . ($plural ? 's' : '') . 's';
        return $output;
    }
}
