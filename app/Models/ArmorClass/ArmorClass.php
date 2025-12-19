<?php

declare(strict_types=1);

namespace App\Models\ArmorClass;

use App\Enums\ArmorClass\ArmorClassSource;
use App\Models\AbilityScores\AbilityScore;
use App\Models\AbstractModel;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CreatureEdition $creatureEdition
 * @property Collection<ArmorClassItem> $items
 */
class ArmorClass extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'armor_classes';

    public function ac(?AbilityScore $dex = null): Attribute
    {
        return Attribute::make(
            // Add 10 (base) plus the dex modifier if we can access it, plus any items (natural or equipment).
            get: fn () => 10 + $this->items->sum(fn (ArmorClassItem $item) => $item->value) + (
                $dex?->modifier ?? $this->creatureEdition?->dex?->modifier ?? 0
            )
        );
    }

    public function creatureEdition(): HasOne
    {
        return $this->hasOne(CreatureEdition::class, 'armor_class_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ArmorClassItem::class, 'armor_class_id');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    /**
     * @property array{
     *     ac: int,
     *     from: string[]
     * }|int[] $value
     */
    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureEdition()->associate($parent);
        $item->save();

        // Sometimes we only have an array of numbers.
        if (is_array($value) && empty($value['from'])) {
            $acItem = new ArmorClassItem();
            $acItem->source_type = ArmorClassSource::NATURAL;
            $acItem->value = $value[0];
        } else {
            foreach ($value['from'] as $fromData) {
                $acItem = ArmorClassItem::from5eJson($value, $item);
                $item->items()->save($acItem);
            }
        }

        $item->save();
        return $item;
    }
}
