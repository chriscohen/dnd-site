<?php

namespace App\Models\Sources;

use App\Enums\Binding;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Binding $binding
 * @property Collection<BoxedSetItem> $boxedSetItems
 * @property Collection $formats
 * @property bool $isPrimary
 * @property ?string $isbn10
 * @property ?string $isbn13
 * @property string $name
 * @property ?int $pages
 * @property ?Carbon $releaseDate
 * @property bool $releaseDateMonthOnly
 * @property Source $source
 * @property string $sourceId
 */
class SourceEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'binding' => Binding::class,
        'isPrimary' => 'boolean',
        'releaseDate' => 'date',
        'releaseDateMonthOnly' => 'boolean',
    ];

    protected function binding(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => empty($value) ? null : Binding::tryFrom($value)->toString(),
        );
    }

    public function boxedSetItems(): HasMany
    {
        return $this->hasMany(BoxedSetItem::class, 'parentId');
    }

    public function formatReleaseDate(): string
    {
        $format = $this->releaseDateMonthOnly ? 'Y-m' : 'Y-m-d';
        return $this->releaseDate->format($format);
    }

    public function formats(): HasMany
    {
        return $this->hasMany(SourceEditionFormat::class, 'sourceEditionId');
    }

    /**
     * @return string[]
     */
    public function getFormatsAsArray(): array
    {
        $output = [];

        foreach ($this->formats as $format) {
            $output[] = $format->format;
        }

        return $output;
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function toArrayFull(): array
    {
        return [
            'binding' => $this->binding,
            'boxedSetItems' => ModelCollection::make($this->boxedSetItems)
                ->toArray($this->renderMode),
            'isPrimary' => $this->isPrimary,
            'isbn10' => $this->isbn10,
            'isbn13' => $this->isbn13,
            'pages' => $this->pages,
            'releaseDate' => $this->formatReleaseDate(),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
