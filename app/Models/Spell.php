<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property string $index
 * @property string $name
 * @property string $description
 * @property string $higher_level
 * @property int $range_number
 * @property Distance $range_unit
 */
class Spell extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function rangeUnit(): BelongsTo
    {
        return $this->belongsTo(Distance::class, 'range_unit');
    }
}
