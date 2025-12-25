<?php

declare(strict_types=1);

namespace App\Models\AbilityScores;

use App\Enums\AbilityScoreType;
use App\Models\AbstractModel;
use App\Models\Actors\ActorType;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property bool $is_proficient
 * @property int $modifier
 * @property ActorType|CreatureEdition $parent
 * @property AbilityScoreType $type
 * @property int $value
 */
class AbilityScore extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_proficient' => 'boolean',
            'type' => AbilityScoreType::class
        ];
    }

    public function getSavingThrow(?int $proficiencyBonus): int
    {
        if ($this->is_proficient && !empty($proficiencyBonus)) {
            return $proficiencyBonus + $this->modifier;
        } else {
            return $this->modifier;
        }
    }

    public function modifier(): Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->value === null ? null : (int) floor(($this->value - 10) / 2),
        );
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
        return [];
    }

    public static function getModifier(int $abilityScore): int
    {
        return (int) floor(($abilityScore - 10) / 2);
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    public static function fromNumber(
        int $value,
        AbilityScoreType|string $type,
        ?ModelInterface $parent = null,
        bool $isProficient = false
    ): static {
        if (is_string($type)) {
            $type = AbilityScoreType::tryFromString($type) ??
                throw new \InvalidArgumentException('Invalid ability score type: ' . $type);
        }

        $item = new static();
        $item->parent()->associate($parent);
        $item->is_proficient = $isProficient;
        $item->type = $type;
        $item->value = $value;
        $item->save();
        return $item;
    }

}
