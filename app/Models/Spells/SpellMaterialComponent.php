<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\Items\Item;
use App\Models\Items\ItemEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property bool $is_consumed
 * @property Uuid $item_edition_id
 * @property ItemEdition $itemEdition
 * @property int $quantity
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
            'is_consumed' => $this->is_consumed,
            //'item_edition' => $this->itemEdition->toArray($this->renderMode),
            'quantity' => $this->quantity,
            //'spell_edition' => $this->spellEdition->toArray($this->renderMode),
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
        return [];
    }
}
