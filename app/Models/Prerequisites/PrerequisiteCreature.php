<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Models\Creatures\Creature;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Creature $creature
 * @property PrerequisiteCreatureGroup $group
 * @property ?string $name
 */
class PrerequisiteCreature extends AbstractPrerequisite
{
    use HasUuids;

    public $timestamps = false;

    public function group(): BelongsTo
    {
        return $this->belongsTo(PrerequisiteCreatureGroup::class);
    }

    public function creature(): BelongsTo
    {
        return $this->belongsTo(Creature::class);
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            // Creature could be a reference to a Creature entity, or just a string.
            'creature' => $this->name ?? $this->creature->toArrayShort(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);

        if (empty($value['displayEntry'])) {
            // If there's no "displayEntry" property, we are referencing a Creature entity.
            $creature = Creature::query()->where('id', $value)->firstOrFail();
            $item->creature()->associate($creature);
        } else {
            // We are describing a creature or group by string.
            $item->name = $value['displayEntry'];
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
