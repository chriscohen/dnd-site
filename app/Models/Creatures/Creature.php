<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<CreatureEdition> $editions
 * @property Collection<Creature> $subspecies
 */
class Creature extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $incrementing = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureEdition::class);
    }

    public function subspecies(): HasMany
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
        return static::fromInternalJson($value, $parent);
    }
}
