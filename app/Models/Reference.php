<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Source $source
 * @property ?int $page_from
 * @property ?int $page_to
 */
class Reference extends AbstractModel
{
    use HasUuids;
}
