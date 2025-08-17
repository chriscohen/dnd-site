<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $uuid
 */
class AttackType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
}
