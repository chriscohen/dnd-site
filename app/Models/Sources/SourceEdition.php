<?php

namespace App\Models\Sources;

use App\Enums\Binding;
use App\Enums\JsonRenderMode;
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
 * @property bool $is_primary
 * @property ?string $isbn10
 * @property ?string $isbn13
 * @property string $name
 * @property ?int $pages
 * @property ?Carbon $release_date
 * @property bool $release_date_month_only
 * @property Source $source
 * @property string $source_id
 */
class SourceEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'binding' => Binding::class,
        'is_primary' => 'boolean',
        'release_date' => 'date',
        'release_date_month_only' => 'boolean',
    ];

    public array $schema = [
        JsonRenderMode::SHORT->value => [
            'id' => 'string',
            'name' => 'string',
        ],
        JsonRenderMode::FULL->value => [
            'binding' => 'string',
            'formats' => 'getFormatsAsArray()',
            '?pages' => 'int',
            'is_primary' => 'bool',
            '?isbn10' => 'string',
            '?isbn13' => 'string',
        ],
    ];

    protected function binding(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Binding::tryFrom($value)->toString(),
        );
    }

    public function boxedSetItems(): HasMany
    {
        return $this->hasMany(BoxedSetItem::class, 'parent_id');
    }

    public function formatReleaseDate(): string
    {
        $format = $this->release_date_month_only ? 'Y-m' : 'Y-m-d';
        return $this->release_date->format($format);
    }

    public function formats(): HasMany
    {
        return $this->hasMany(SourceEditionFormat::class, 'source_edition_id');
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
            'boxed_set_items' => ModelCollection::make($this->boxedSetItems)
                ->toArray($this->renderMode, $this->excluded),
            //'formats' => ModelCollection::make($this->formats)->toString(),
            'is_primary' => $this->is_primary,
            'isbn10' => $this->isbn10,
            'isbn13' => $this->isbn13,
            'pages' => $this->pages,
            'release_date' => $this->formatReleaseDate(),
            'source' => $this->isExcluded(Source::class) ?
                $this->source_id :
                $this->source->toArray($this->renderMode, $this->excluded),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
