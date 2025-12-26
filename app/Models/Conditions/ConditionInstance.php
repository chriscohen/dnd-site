<?php

declare(strict_types=1);

namespace App\Models\Conditions;

use App\Enums\Conditions\ConditionInstanceType;
use App\Enums\Damage\DamageSourceType;
use App\Enums\Damage\DamageType;
use App\Models\AbstractModel;
use App\Models\Actors\ActorType;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents a status condition or damage type immunity, resistance etc on a creature or actor.
 *
 * @property ?ConditionEdition $conditionEdition
 * @property ?DamageType $damage_type
 * @property ?DamageSourceType $damage_source_types
 * @property ActorType|CreatureEdition $entity
 * @property ?string $note
 * @property ConditionInstanceType $type
 */
class ConditionInstance extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'damage_type' => DamageType::class,
            'damage_source_types' => AsEnumCollection::of(DamageSourceType::class),
            'type' => ConditionInstanceType::class,
        ];
    }

    public function conditionEdition(): BelongsTo
    {
        return $this->belongsTo(ConditionEdition::class, 'condition_edition_id');
    }

    public function entity(): MorphTo
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
        return [];
    }

    /**
     * @param  array{
     *     name: string,
     *     type: ConditionInstanceType
     *     nonmagical: ?bool
     * }  $value
     * @param  CreatureEdition|null  $parent
     */
    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);

        // If it's a status condition immunity.
        if ($value['type'] === ConditionInstanceType::STATUS_IMMUNITY) {
            $condition = Condition::query()->where('slug', $value['name'])->firstOrFail();
            $edition = $condition->editions->firstWhere('game_edition', $parent->game_edition);
            $item->conditionEdition()->associate($edition);
            $item->type = ConditionInstanceType::STATUS_IMMUNITY;
        } else {
            $item->type = $value['type'];
            $item->damage_type = DamageType::tryFromString($value['name']);

            if (!empty($value['nonmagical'])) {
                $item->damage_source_types = collect([
                    DamageSourceType::NORMAL,
                    DamageSourceType::SILVER,
                    DamageSourceType::ADAMANTINE
                ]);
            }
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
