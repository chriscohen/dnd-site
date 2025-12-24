<?php

declare(strict_types=1);

namespace App\Models\AbilityScores;

use App\Enums\AbilityScoreType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property int $modifier
 * @property AbilityScoreType $type
 * @property int $value
 */
class AbilityScore extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'type' => AbilityScoreType::class
        ];
    }

    public function modifier(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => floor(($value - 10) / 2),
        );
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
        ?ModelInterface $parent = null
    ): static {
        if (is_string($type)) {
            $type = AbilityScoreType::tryFromString($type) ??
                throw new \InvalidArgumentException('Invalid ability score type: ' . $type);
        }

        $item = new static();
        $item->type = $type;
        $item->value = $value;
        $item->save();
        return $item;
    }

}
