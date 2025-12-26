<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<CreatureMajorTypeEdition> $editions
 * @property string $plural
 */
class CreatureMajorType extends AbstractModel
{
    use HasUuids;

    protected $fillable = [
        'name',
        'plural',
        'slug',
    ];

    public $timestamps = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CreatureMajorTypeEdition::class, 'creature_major_type_id');
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode, $this->excluded),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'plural' => $this->plural,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->plural = $value['plural'] ?? null;

        foreach ($value['editions'] ?? [] as $editionData) {
            $edition = CreatureMajorTypeEdition::fromInternalJson($editionData, $item);
            $item->editions()->save($edition);
        }

        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $faker = static::getFaker();
        $item = new static();
        $item->name = $faker->words(3, asText: true);
        $item->slug = static::makeSlug($item->name);
        $item->plural = $item->name . 's';
        $item->save();

        $edition = CreatureMajorTypeEdition::generate($item);
        $item->editions()->save($edition);

        $item->save();
        return $item;
    }
}
