<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $name
 */
class Tag extends AbstractModel
{
    use HasUuids;

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
        $item = new static();

        if (is_array($value)) {
            $item->id = $value['id'] ?? Uuid::uuid4();
            $item->name = $value['name'];
        } else {
            $item->id = Uuid::uuid4();
            $item->name = $value;
        }

        $item->save();
        return $item;
    }
}
