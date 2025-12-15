<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Models\Feats\Feature;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use App\Models\ModelInterface;

/**
 * @property Uuid $id
 *
 * @property Feature $feature
 * @property PrerequisiteFeatureGroup $group
 */
class PrerequisiteFeature extends AbstractPrerequisite
{
    use HasUuids;

    public $timestamps = false;

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    public function toArrayFull(): array
    {
        return [
            'feature' => $this->feature->toArrayShort(),
        ];
    }

    public function toArrayShort(): array
    {
        return [];
    }

    public function toArrayTeaser(): array
    {
        return [
            'feature_id' => $this->feature->id,
        ];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();

        $feature = Feature::query()->where('name', $value)->firstOrFail();
        $item->feature()->associate($feature);

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ModelInterface $parent = null): ModelInterface
    {
        return static::fromInternalJson($value, $parent);
    }
}
