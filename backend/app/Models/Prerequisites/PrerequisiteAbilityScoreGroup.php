<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<PrerequisiteAbilityScore> $abilityScores
 * @property PrerequisiteGroup $group
 */
class PrerequisiteAbilityScoreGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function abilityScores(): HasMany
    {
        return $this->hasMany(PrerequisiteAbilityScore::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(PrerequisiteGroup::class);
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();

        $item->save();
        return $item;
    }
}
