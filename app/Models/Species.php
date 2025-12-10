<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 */
class Species extends AbstractModel
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
            'slug' => $this->slug,
            'name' => $this->name,
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
