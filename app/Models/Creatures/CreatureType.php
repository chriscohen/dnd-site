<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property string $name
 */
class CreatureType extends AbstractModel
{
    use HasUuids;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureTypeEdition::class, 'creature_type_id');
    }
}
