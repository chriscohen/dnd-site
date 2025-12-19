<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelInterface;

/**
 * Creature origin is always 4th edition only.
 *
 * @property string $id
 * @property string $name
 *
 * @property string $origin
 * @property string $plural
 */
class CreatureOrigin extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'origin' => $this->origin,
            'plural' => $this->plural,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->origin = $value['origin'];
        $item->plural = $value['plural'];
        $item->save();
        return $item;
    }
}
