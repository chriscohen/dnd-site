<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Groups a collection of fields used to describe the type of creature.
 *
 * Each edition did this slightly differently.
 *
 * @property string $id
 *
 * @property Collection<CreatureEdition> $creatures
 * @property GameEdition $game_edition
 * @property CreatureMajorType $majorType
 * @property ?CreatureOrigin $origin
 */
class CreatureType extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'game_edition' => GameEdition::class,
        ];
    }

    public function creatures(): HasMany
    {
        return $this->hasMany(CreatureEdition::class, 'creature_edition_id');
    }

    public function majorType(): BelongsTo
    {
        return $this->belongsTo(CreatureMajorType::class, 'creature_major_type_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(CreatureOrigin::class, 'creature_origin_id');
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        throw new \Exception('Not implemented');
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->game_edition = GameEdition::FIFTH;

        if (is_array($value)) {
            if (empty($value['type'])) {
                throw new \InvalidArgumentException('Creature type is required.');
            }

            $typeName = $value['type'];
        } else {
            $typeName = $value;
        }

        $type = CreatureMajorType::query()->where('slug', $typeName)->firstOrFail();
        $item->majorType()->associate($type);

        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $item = new static();
        $item->game_edition = GameEdition::FIFTH;

        $origin = CreatureOrigin::generate($item);
        $item->origin()->associate($origin);

        $majorType = CreatureMajorType::generate($item);
        $item->majorType()->associate($majorType);

        $item->save();
        return $item;
    }
}
