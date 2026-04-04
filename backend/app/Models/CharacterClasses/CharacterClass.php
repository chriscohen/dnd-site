<?php

declare(strict_types=1);

namespace App\Models\CharacterClasses;

use App\Models\AbstractModel;
use App\Models\Media\Media;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<CharacterClassEdition> $editions
 * @property ?Media $image
 */
class CharacterClass extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function editions(): HasMany
    {
        return $this->hasMany(CharacterClassEdition::class, 'character_class_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function isPrestige(): bool
    {
        foreach ($this->editions as $edition) {
            if ($edition->is_prestige) {
                return true;
            }
        }

        return false;
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
        return [
            'image' => $this->image?->toArray($this->renderMode),
            'is_prestige' => $this->isPrestige(),
        ];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->name = $value['name'];

        if (!empty($value['image'])) {
            $media = Media::fromInternalJson([
                'filename' => '/classes/' . $value['image'],
                'collection_name' => 'classes',
            ]);
            $item->image()->associate($media);
        }

        $item->save();
        return $item;
    }
}
