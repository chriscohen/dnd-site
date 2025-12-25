<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\PerLevelMode;
use App\Enums\Units\TimeUnit;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @param Uuid $id
 *
 * @param int $number
 * @param ?int $plus
 * @param ?PerLevelMode $plus_type
 * @param SpellEdition $spellEdition
 * @param TimeUnit $unit
 */
class SpellCastingTime extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'unit' => TimeUnit::class,
        ];
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class);
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

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->spellEdition()->associate($parent);
        $item->number = $value['number'];
        $item->unit = TimeUnit::tryFromString($value['unit']);
        $item->save();
        return $item;
    }
}
