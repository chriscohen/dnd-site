<?php

declare(strict_types=1);

namespace App\Models\ArmorClass;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ArmorClass extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'armor_classes';

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
}
