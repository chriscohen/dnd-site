<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;

/**
 * @property string $id
 * @property string $name
 */
class SpellComponentType extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;
}
