<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CreatureType $creatureType
 * @property $creature_type_id
 * @property Collection<Reference> $references
 */
class CreatureTypeEdition extends AbstractModel
{
    use HasUuids;

    public function creatureType(): BelongsTo
    {
        return $this->belongsTo(CreatureType::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }
}
