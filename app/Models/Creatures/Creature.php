<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<CreatureEdition> $editions
 * @property ?Creature $parent
 * @property Collection<Creature> $children
 */
class Creature extends AbstractModel
{
    use HasUuids;
    use Searchable;

    public $timestamps = false;
    public $incrementing = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureEdition::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Creature::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Creature::class, 'parent_id');
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
        $existing = static::query()->where('name', $value['name'])->first();

        if (!empty($existing)) {
            Reference::from5eJson([
                'source' => $value['source'],
                'page' => $value['page'] ?? null,
            ], $existing);
            return $existing;
        }

        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        $item->save();

        $edition = CreatureEdition::fromInternalJson($value, $item);
        $item->editions()->save($edition);

        Reference::from5eJson([
            'source' => $value['source'],
            'page' => $value['page'] ?? null,
        ], $item);

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        // Keys are the names of creatures. Values are slugs for their parent type.
        $map = [
            'Dragonborn (' => 'dragonborn',
            'Elf (' => 'elf',
            'Gnome (' => 'gnome',
            'Goblin (' => 'goblin',
            'Human (' => 'human',
            'Minotaur (' => 'minotaur',
            'Orc (' => 'orc',
            'Yuan-ti Pureblood' => 'yuan-ti',
        ];

        // Try to get an existing item.
        $existing = static::query()->where('name', $value['name'])->first();
        $item = $existing ?? new static();
        $item->name = $value['name'];
        $item->slug = static::makeSlug($value['name']);

        // Do we need to look for a parent type?
        foreach ($map as $prefix => $slug) {
            if (str_starts_with($value['name'], $prefix)) {
                $parentEntity = Creature::query()->where('slug', $slug)->firstOrFail();
                $item->parent()->associate($parentEntity);
            }
        }

        $item->save();

        // Edition.
        $edition = CreatureEdition::from5eJson($value, $item);
        $item->editions()->save($edition);

        $item->save();
        return $item;
    }
}
