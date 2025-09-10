<?php

namespace App\Models;

use App\Enums\JsonRenderMode;
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

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $short = [
            'id' => $this->id,
            'page_from' => $this->page_from ?? null,
            'page_to' => $this->page_to ?? null,
            'source' => $this->edition->source->name,
        ];

        if ($mode == JsonRenderMode::SHORT) {
            return $short;
        }

        unset($short['source']);

        return array_merge_recursive($short, [
            'entity' => $this->entity->toArray(),
            'source_edition' => $this->edition->toArray($mode),
        ]);
    }
}
