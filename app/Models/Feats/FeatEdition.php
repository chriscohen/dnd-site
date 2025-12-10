<?php

declare(strict_types=1);

namespace App\Models\Feats;

use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\Prerequisites\Prerequisite;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property ?string $description
 * @property Feat $feat
 * @property GameEdition $game_edition
 * @property Collection<Prerequisite> $prerequisites
 * @property Collection<Reference> $references
 */
class FeatEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
    ];

    public function feat(): BelongsTo
    {
        return $this->belongsTo(Feat::class);
    }

    public function getName(): string
    {
        return $this->feat->getName();
    }

    public function getSlug(): string
    {
        return $this->feat->getSlug();
    }

    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function toArrayFull(): array
    {
        return [
            'feat' => $this->feat->toArray(JsonRenderMode::SHORT),
            'prerequisites' => ModelCollection::make($this->prerequisites)->toArray($this->renderMode),
            'references' => ModelCollection::make($this->references)->toArray(),
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
            'description' => $this->description,
            'game_edition' => $this->game_edition->toStringShort(),
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
