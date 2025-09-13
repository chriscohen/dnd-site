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

    public array $schema = [
        JsonRenderMode::SHORT->value => [
            'id' => 'string',
            '?page_from' => 'int',
            '?page_to' => 'int',
            'source' => 'edition->source->name',
        ],
        JsonRenderMode::FULL->value => [
            'entity' => ModelInterface::class,
            'edition' => SourceEdition::class,
        ],
    ];

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
}
