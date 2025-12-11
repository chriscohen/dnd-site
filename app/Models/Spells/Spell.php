<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\Media;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<SpellEdition> $editions
 * @property ?Media $image
 * @property string $name
 * @property string $slug
 */
class Spell extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'range_is_touch' => 'boolean',
        'range_is_self' => 'boolean',
    ];

    public function editions(): HasMany
    {
        return $this->hasMany(SpellEdition::class, 'spell_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function toArrayFull(): array
    {
        return [];
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
        /** @var SpellEdition $edition */
        $edition = $this->editions()->where('is_default', true)->first() ?? $this->editions()->first();

        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
            'image' => $this->image?->toArray($this->renderMode),
            'lowestLevel' => $edition->getLowestLevel(),
            'rarity' => $edition->rarity->toString(),
            'school' => $edition->school?->name,
        ];
    }


    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();

        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        if (!empty($value['image'])) {
            $image = Media::fromInternalJson([
                'filename' => '/spells/' . $value['image'],
            ], $item);
            $item->image()->associate($image);
        }

        foreach ($value['editions'] ?? [] as $editionData) {
            $edition = SpellEdition::fromInternalJson($editionData, $item);
            $item->editions()->save($edition);
        }

        $item->save();
        return $item;
    }

    public static function fromFeJson(array $value): self
    {
        $item = new static();

        $item->save();
        return $item;
    }
}
