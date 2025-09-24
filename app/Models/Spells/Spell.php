<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\Media;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<SpellEdition> $editions
 * @property Media $image
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
        return $this->hasMany(SpellEdition::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
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
            'image' => $this->image->toArray($this->renderMode, $this->excluded),
        ];
    }

    public function toArrayTeaser(): array
    {
        /** @var SpellEdition $edition */
        $edition = $this->editions()->where('is_default', true)->first();

        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode, $this->excluded),
            'image' => $this->image->toArray($this->renderMode, $this->excluded),
            'lowest_level' => $edition->getLowestLevel(),
            'school' => $edition->school->name,
        ];
    }
}
