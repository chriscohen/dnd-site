<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\Items\ItemType;
use App\Models\Items\ItemTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $description
 * @property bool $is_consumed
 * @property bool $is_focus
 * @property bool $is_plural
 * @property Uuid $item_edition_id
 * @property ItemTypeEdition $itemEdition
 * @property ?int $minimum_value
 * @property ?int $quantity
 * @property ?string $quantity_text
 * @property Uuid $spell_edition_id
 * @property SpellEdition $spellEdition
 */
class SpellMaterialComponent extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'is_consumed' => 'boolean',
        'is_focus' => 'boolean',
        'is_plural' => 'boolean',
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
        return $this->belongsTo(ItemTypeEdition::class);
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'description' => $this->description,
            'isConsumed' => $this->is_consumed,
            'isFocus' => $this->is_focus,
            'isPlural' => $this->is_plural,
            'minimumValue' => empty($this->minimum_value) ? null : $this->formatPrice($this->minimum_value),
            'quantity' => $this->quantity,
            'quantityText' => $this->quantity_text,
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->spellEdition()->associate($parent);

        $itemItem = ItemType::query()->where('slug', $value['item'])->firstOrFail();
        $itemEdition = $itemItem->defaultEdition();
        $item->itemEdition()->associate($itemEdition);

        $item->description = $value['description'] ?? null;
        $item->quantity = $value['quantity'] ?? 1;
        $item->quantity_text = $value['quantityText'] ?? null;
        $item->is_consumed = $value['isConsumed'] ?? false;
        $item->is_plural = $value['isPlural'] ?? false;


        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ModelInterface $parent = null): SpellMaterialComponent
    {
        $item = new static();
        $item->spellEdition()->associate($parent);
        $item->description = $value['text'] ?? null;
        $item->is_consumed = $value['consume'] ?? false;

        $item->save();
        return $item;
    }
}
