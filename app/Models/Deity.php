<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property string $id
 */
class Deity extends AbstractModel
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
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
