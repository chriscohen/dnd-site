<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property string $id
 * @property string $short_name
 * @property double $per_meter
 */
class Distance extends AbstractModel
{
    public $timestamps = false;
}
