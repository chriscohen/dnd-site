<?php

namespace App\Models;

use App\Models\Sources\SourceEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property SourceEdition $edition
 * @property ModelInterface $entity
 * @property ?int $page_from
 * @property ?int $page_to
 *
 */
class Reference extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function getName(): string
    {
        return $this->edition->source->name;
    }

    public function getSlug(): string
    {
        return $this->edition->source?->slug;
    }

    public function toArrayFull(): array
    {
        return [
            'entity' => $this->entity->toArray($this->renderMode),
            'edition' => $this->edition->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'page_from' => $this->page_from,
            'page_to' => $this->page_to,
            'source' => $this->edition->source?->name,
            'slug' => $this->edition->source?->slug,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'image' => $this->edition->source->coverImage->toArray($this->renderMode),
        ];
    }
}
