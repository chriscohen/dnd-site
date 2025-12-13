<?php

declare(strict_types=1);

namespace App\Models\Actors;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeedGroup;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ActorType $actorType
 * @property MovementSpeedGroup $movementSpeeds
 */
class ActorTypeEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function actorType(): BelongsTo
    {
        return $this->belongsTo(ActorType::class);
    }

    public function movementSpeeds(): MorphOne
    {
        return $this->morphOne(MovementSpeedGroup::class, 'parent');
    }

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
