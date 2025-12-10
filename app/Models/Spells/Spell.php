<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\Media;
use App\Models\ModelCollection;
use App\Models\Sources\Source;
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

    public function editions(): HasMany
    {
        return $this->hasMany(SpellEdition::class, 'spellId');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'imageId');
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


    public static function fromInternalJson(array $value): static
    {
        // TODO: unfinished
        $item = new static();

        return $item;
    }

    public static function fromFeJson(array $value): self
    {
        // TODO: unfinished
        $item = new static();

        $item->name = $value['name'];
        if (!empty($value['source'])) {
            $source = Source::query()->where('shortName', $value['source'])->firstOrFail();
            $item->source = null;
        }



        return $item;
    }
}
