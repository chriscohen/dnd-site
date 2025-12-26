<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\Creatures\CreatureAgeType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property CreatureEdition $creatureEdition
 * @property CreatureAgeType $type
 * @property int $value
 */
class CreatureAge extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'type' => CreatureAgeType::class,
        ];
    }

    public function creatureEdition(): BelongsTo
    {
        return $this->belongsTo(CreatureEdition::class, 'creature_edition_id');
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

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    /**
     * @param  array|string|int  $value
     */
    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureEdition()->associate($parent);
        $item->type = CreatureAgeType::tryFromString($value['type']);
        $item->value = $value['value'];
        $item->save();
        return $item;
    }
}

