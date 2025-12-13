<?php

declare(strict_types=1);

namespace App\Models\Species;

use App\Enums\CreatureSize;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property SpeciesEdition $parent
 * @property CreatureSize $size
 */
class Size extends AbstractModel
{
    public $timestamps = false;

    public $casts = [
        'size' => CreatureSize::class,
    ];

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'size' => $this->size->toStringShort(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);
        $item->size = CreatureSize::tryFromString($value);

        $item->save();
        return $item;
    }
}
