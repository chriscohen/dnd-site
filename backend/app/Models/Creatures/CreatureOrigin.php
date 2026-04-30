<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CreatureType origin is always 4th edition only.
 *
 * @property string $id
 * @property string $name
 *
 * @property Collection<CreatureMainTypeGroup> $creatureMainTypeGroups
 * @property string $origin
 * @property string $plural
 */
class CreatureOrigin extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function creatureMainTypeGroups(): HasMany
    {
        return $this->hasMany(CreatureMainTypeGroup::class, 'creature_origin_id');
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->origin = $value['origin'];
        $item->plural = $value['plural'];
        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $faker = static::getFaker();
        $item = new static();
        $item->name = $faker->words(3, asText: true);
        $item->id = static::makeSlug($item->name);
        $item->origin = $faker->words(3, asText: true);
        $item->plural = $item->name . 's';
        $item->save();
        return $item;
    }
}
