<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $name
 */
class AttackType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function toArrayLong(): array
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
}
