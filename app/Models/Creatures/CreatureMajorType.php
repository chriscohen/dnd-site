<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<CreatureMajorTypeEdition> $editions
 * @property string $plural
 */
class CreatureMajorType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureMajorTypeEdition::class, 'creature_major_type_id');
    }
}
