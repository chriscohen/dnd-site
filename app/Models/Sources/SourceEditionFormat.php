<?php

declare(strict_types=1);

namespace App\Models\Sources;

use App\Enums\Sources\SourceFormat;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property SourceEdition $edition
 * @property Uuid $source_edition_id
 * @property SourceFormat $format
 */
class SourceEditionFormat extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'format' => SourceFormat::class,
    ];

    protected function format(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => SourceFormat::tryFrom($value)->toString(),
        );
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }

    public function toArrayFull(): array
    {
        return [
            'edition' => $this->edition->toArray($this->renderMode, $this->excluded),
            'format' => $this->format,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function toString(): string
    {
        return $this->format;
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->edition()->associate($parent);
        $item->format = SourceFormat::tryFromString($value);
        $item->save();
        return $item;
    }
}
