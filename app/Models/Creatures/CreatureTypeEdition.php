<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<CreatureEdition> $editions
 * @property CreatureType $creatureType
 * @property $creature_type_id
 * @property GameEdition $game_edition
 * @property Collection<Reference> $references
 */
class CreatureTypeEdition extends AbstractModel
{
    use HasUuids;

    public $incrementing = false;
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'game_edition' => GameEdition::class,
        ];
    }

    public function creatureEditions(): HasMany
    {
        return $this->hasMany(CreatureEdition::class);
    }

    public function creatureType(): BelongsTo
    {
        return $this->belongsTo(CreatureType::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'gameEdition' => $this->game_edition->toStringShort()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        return new static();
    }
}
