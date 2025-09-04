<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $name
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;
}
