<?php

declare(strict_types=1);

namespace App\Models\AbilityScores;

use App\Enums\AbilityScoreType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property AbilityScoreType $ability_score
 * @property ?AbilityScoreModifierGroup $group
 * @property int $value
 */
class AbilityScoreModifier extends AbstractModel
{
    public $timestamps = false;

    public $casts = [
        'ability_score' => AbilityScoreType::class,
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(AbilityScoreModifierGroup::class, 'ability_score_modifier_group_id');
    }

    public function toArrayFull(): array
    {
        return [
            'group_id' => $this->group?->id,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'ability_score' => $this->ability_score,
            'modifier' => $this->value,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * @property array{
     *     'ability': string,
     *     'modifier': int,
     * } $value
     */
    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);
        $item->ability_score = AbilityScoreType::tryFromString($value['ability']);
        $item->value = $value['modifier'];

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
