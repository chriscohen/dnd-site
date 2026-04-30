<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<PrerequisiteFeature> $features
 * @property PrerequisiteGroup $group
 */
class PrerequisiteFeatureGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function features(): HasMany
    {
        return $this->hasMany(PrerequisiteFeature::class);
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
