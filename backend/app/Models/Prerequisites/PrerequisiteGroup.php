<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\Prerequisites\PrerequisiteType;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<PrerequisiteAbilityScore> $abilityScores
 * @property ?PrerequisiteLevel $level
 * @property ModelInterface $parent
 */
class PrerequisiteGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function abilityScores(): HasMany
    {
        return $this->hasMany(PrerequisiteAbilityScore::class);
    }

    public function level(): HasOne
    {
        return $this->hasOne(PrerequisiteLevel::class);
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
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
        return [
            'values' => ModelCollection::make($this->values)->toArray(),
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(PrerequisiteValue::class);
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);

        $item->save();
        return $item;
    }
}
