<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\AbilityScoreType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;
use App\Models\ModelInterface;

/**
 * @property Uuid $id
 *
 * @property AbilityScoreType $ability_score_type
 * @property PrerequisiteGroup $group
 * @property ?int $maximum
 * @property ?int $minimum
 */
class PrerequisiteAbilityScore extends AbstractPrerequisite
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'ability_score_type' => AbilityScoreType::class,
    ];

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

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);
        $item->ability_score_type = AbilityScoreType::tryFromString($value['ability']);
        $item->minimum = $value['minimum'] ?? null;
        $item->maximum = $value['maximum'] ?? null;
        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
