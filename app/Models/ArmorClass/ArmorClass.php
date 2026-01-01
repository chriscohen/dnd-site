<?php

declare(strict_types=1);

namespace App\Models\ArmorClass;

use App\Enums\ArmorClass\ArmorClassSource;
use App\Models\AbilityScores\AbilityScore;
use App\Models\AbstractModel;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 *
 * @property ?bool $braces
 * @property ?string $condition
 * @property CreatureTypeEdition $creatureTypeEdition
 * @property Collection<ArmorClassItem> $items
 * @property ?int $value
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
                $dex?->modifier ?? $this->creatureTypeEdition?->dex?->modifier ?? 0
            )
        );
    }

    public function creatureTypeEdition(): BelongsTo
    {
        return $this->belongsTo(CreatureTypeEdition::class, 'creature_type_edition_id');
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

    public function value(): Attribute
    {
        return Attribute::make(
            get: fn () => 10 + $this->items->sum(fn (ArmorClassItem $item) => $item->value)
        );
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    /**
     * @property array{
     *     'ac': int,
     *     'braces': ?bool,
     *     'condition': ?string,
     *     'from': string[]
     * }|int $value
     */
    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureTypeEdition()->associate($parent);
        $item->save();

        // Sometimes we have a number, and sometimes a data structure.
        if (is_array($value)) {
            $item->braces = $value['braces'] ?? false;
            $item->condition = $value['condition'] ?? null;

            foreach ($value['from'] ?? [] as $fromData) {
                $acItem = ArmorClassItem::from5eJson([
                    'ac' => $value['ac'],
                    'from' => $fromData
                ], $item);
                $item->items()->save($acItem);
            }
        } else {
            // If we just have a number, add one item to represent natural armor.
            $acItem = new ArmorClassItem();
            $acItem->armorClass()->associate($item);
            $acItem->source_type = ArmorClassSource::NATURAL;
            $acItem->value = $value;
            $acItem->save();
            $item->items()->save($acItem);
        }


        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureTypeEdition()->associate($parent);
        $item->save();
        $item->items()->saveMany([
            ArmorClassItem::generate($item),
            ArmorClassItem::generate($item),
        ]);
        $item->braces = mt_rand(0, 1) === 1;
        $item->condition = 'random text';
        $item->save();
        return $item;
    }
}
