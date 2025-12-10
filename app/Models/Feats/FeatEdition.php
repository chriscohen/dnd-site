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
 * @property GameEdition $gameEdition
 * @property Collection<Prerequisite> $prerequisites
 * @property Collection<Reference> $references
 */
class FeatEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
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
            'feat' => $this->feat->toArray($this->renderMode),
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
            'gameEdition' => $this->gameEdition->toStringShort(),
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
