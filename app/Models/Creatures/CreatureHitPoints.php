<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\Dice\DiceFormula;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property ?int $average
 * @property CreatureEdition $creatureEdition
 * @property ?string $description
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

        if (!empty($value['average'])) {
            $item->average = $value['average'];
        }
        if (!empty($value['formula'])) {
            $item->formula = $value['formula'];
        }

        // Sometimes there is just a "special" key, so we will treat that as "average".
        if (!empty($value['special'])) {
            if (is_string($value['special'])) {
                // Sometimes the special field contains a description after the number of hit points.
                $pieces = explode(' ', $value['special'], 2);
                $item->average = $pieces[0];

                if (!empty($pieces[1])) {
                    $item->description = $pieces[1];
                }
            } else {
                $item->special = $value['special'];
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
