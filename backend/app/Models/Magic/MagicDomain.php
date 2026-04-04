<?php

namespace App\Models\Magic;

use App\Models\AbstractModel;
use App\Models\ModelInterface;

/**
 * @property string $id
 * @property string $name
 */
class MagicDomain extends AbstractModel
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
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        throw new \Exception('Not implemented');
    }
}
