<?php

namespace App\Models;

use App\Exceptions\RecordNotFoundException;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            'edition' => $this->edition->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        $output = [
            'id' => $this->id,
            'source' => $this->edition->source?->name,
            'slug' => $this->edition->source?->slug,
        ];

        if (!empty($this->page_from)) {
            $output['pageFrom'] = $this->page_from;
        }
        if (!empty($this->page_to)) {
            $output['pageTo'] = $this->page_to;
        }
        if (!empty($this->edition->source->shortName)) {
            $output['shortName'] = $this->edition->source->shortName;
        }

        return $output;
    }

    public function toArrayTeaser(): array
    {
        return [
            'image' => $this->edition?->source?->coverImage?->toArray($this->renderMode),
        ];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->page_from = $value['pageFrom'] ?? null;
        $item->page_to = $value['pageTo'] ?? null;

        if (!empty($value['editionId'])) {
            $sourceEdition = SourceEdition::query()
                ->where('id', $value['editionId'])
                ->firstOrFail();
            $item->edition()->associate($sourceEdition);
        } else {
            $source = Source::query()->where('slug', $value['source'])->firstOrFail();
            $sourceEdition = $source->primaryEdition();
            $item->edition()->associate($sourceEdition);
        }

        $item->save();
        return $item;
    }

    /**
     * @param  array|string  $value
     * @throws RecordNotFoundException
     */
    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->entity()->associate($parent);

        try {
            $source = Source::query()->where('shortName', $value['source'])->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new RecordNotFoundException("[WARNING] Could not find source with shortName: {$value['source']}");
        }

        $item->edition()->associate($source->primaryEdition());

        if (!empty($value['page'])) {
            $item->page_from = $value['page'];
        }

        $item->save();
        return $item;
    }
}
