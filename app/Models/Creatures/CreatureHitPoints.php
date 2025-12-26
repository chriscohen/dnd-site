<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\Dice\DiceFormula;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 * @property ?int $average
 * @property CreatureEdition $creatureEdition
 * @property DiceFormula $formula
 */
class CreatureHitPoints extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'formula' => DiceFormula::class,
        ];
    }

    public function creatureEdition(): HasOne
    {
        return $this->hasOne(CreatureEdition::class, 'creature_hit_points_id');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'average' => $this->average,
            'formula' => $this->formula->__toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * @property array{
     *     'average': int,
     *      'formula': string,
     * } $value
     */
    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        /** @var CreatureEdition $parent */
        if (!empty($parent)) {
            $item->creatureEdition()->save($parent);
        }

        $item->average = $value['average'];
        $item->formula = $value['formula'];

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
