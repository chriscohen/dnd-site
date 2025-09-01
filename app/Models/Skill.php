<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property string $name
 * @property Attribute $related_attribute
 */
class Skill extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'related_attribute' => Attribute::class,
    ];
}
