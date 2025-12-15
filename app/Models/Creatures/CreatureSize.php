<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\CreatureSizeUnit;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CreatureEdition $parent
 * @property CreatureSizeUnit $size
 */
class CreatureSize extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'size' => CreatureSizeUnit::class,
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
        $item->size = CreatureSizeUnit::tryFromString($value);

        $item->save();
        return $item;
    }
}
