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
 * @property Uuid $creatureTypeId
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

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }

    public function toArrayFull(): array
    {
        throw new \Exception('Not implemented');
    }

    public function toArrayShort(): array
    {
        throw new \Exception('Not implemented');
    }

    public function toArrayTeaser(): array
    {
        throw new \Exception('Not implemented');
    }
}
