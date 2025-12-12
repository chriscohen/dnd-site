<?php

declare(strict_types=1);

namespace App\Models\Sources;

use App\Enums\Sources\SourceContentsType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $name
 *
 * @property SourceEdition $edition
 * @property Collection<SourceContentsHeader> $headers
 * @property Uuid $source_edition_id
 * @property string|int $ordinal
 * @property SourceContentsType $type
 */
class SourceContents extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'source_contents';

    public $casts = [
        'type' => SourceContentsType::class,
    ];

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }

    public function headers(): HasMany
    {
        return $this->hasMany(SourceContentsHeader::class, 'source_contents_id');
    }

    public static function fromInternalJson(array|int|string $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->edition()->associate($parent);
        $item->name = $value['name'];
        // Sometimes there isn't an "ordinal" in the source, so we'll assume these go at the start.
        $item->ordinal = $value['ordinal']['identifier'] ?? '0';
        if (!empty($value['ordinal']['type'])) {
            $item->type = SourceContentsType::tryFromString($value['ordinal']['type']);
        }
        $item->save();
        foreach ($value['headers'] ?? [] as $headerData) {
            // Sometimes there are subheaders too, so we'll skip these by only considering strings.
            if (is_string($headerData)) {
                $header = SourceContentsHeader::fromInternalJson($headerData, $item);
                $item->headers()->save($header);
            }
        }
        $item->save();
        return $item;
    }

    /**
     * @param array{
     *     name: string,
     *     ordinal: array{
     *         identifier: int,
     *         type: string
     *     },
     *     headers: string[]
     * } $value
     */
    public static function fromFeJson(array $value, ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'edition_id' => $this->edition->id,
            'source' => $this->edition->source->name,
            'headers' => $this->headers->map(fn (SourceContentsHeader $header) => $header->toArrayFull())->toArray(),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'name' => $this->name,
            'order' => $this->order,
            'type' => $this->type->toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
