<?php

namespace App\Models;

use App\Enums\Binding;
use App\Enums\JsonRenderMode;
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

    protected function binding(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Binding::tryFrom($value)->toString(),
        );
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

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $format = $this->release_date_month_only ? 'Y-m' : 'Y-m-d';

        $short = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($mode == JsonRenderMode::SHORT) {
            return $short;
        }

        return array_merge_recursive($short, [
            'binding' => $this->binding,
            'formats' => $this->getFormatsAsArray(),
            'pages' => $this->pages,
            'isbn10' => $this->isbn10 ?? null,
            'isbn13' => $this->isbn13 ?? null,
            'release_date' => $this->release_date->format($format),
            'source' => $this->source->toArray($mode),
        ]);
    }
}
