<?php

namespace App\Models\Magic;

use App\Models\AbstractModel;

/**
 * @property string $id
 * @property string $name
 */
class MagicDomain extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
