<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\Items\Item;
use App\Models\Items\ItemEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $is_consumed
 * @property Item $item
 * @property Uuid $item_edition_id
 * @property ItemEdition $itemEdition
 * @property int $quantity
 * @property Spell $spell
 * @property Uuid $spell_edition_id
 * @property SpellEdition $spellEdition
 */
class SpellMaterialComponent extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function item(): Item
    {
        return $this->itemEdition->item;
    }

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

    public function spell(): Spell
    {
        return $this->spellEdition->spell;
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class);
    }

    public function toArrayLong(): array
    {
        return [
            'is_consumed' => $this->is_consumed,
            'item' => $this->item->toArray($this->renderMode, $this->excluded),
            'item_edition' => $this->itemEdition->toArray($this->renderMode, $this->excluded),
            'quantity' => $this->quantity,
            'spell' => $this->spell->toArray($this->renderMode, $this->excluded),
            'spell_edition' => $this->spellEdition->toArray($this->renderMode, $this->excluded),
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
}
