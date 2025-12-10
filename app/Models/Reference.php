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
 * @property ?int $pageFrom
 * @property ?int $pageTo
 *
 */
class Reference extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'sourceEditionId');
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
            'edition' => $this->edition->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'pageFrom' => $this->pageFrom,
            'pageTo' => $this->pageTo,
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

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
