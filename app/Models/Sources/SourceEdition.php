<?php

namespace App\Models\Sources;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
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
 * @property Collection<SourceContents> $contents
 * @property Collection<SourceEditionFormat> $formats
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
            get: fn (?int $value) => empty($value) ? null : Binding::tryFrom($value)->toString(),
        );
    }

    public function boxedSetItems(): HasMany
    {
        return $this->hasMany(BoxedSetItem::class, 'parent_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(SourceContents::class, 'source_edition_id');
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
                ->toArray($this->renderMode),
            'formats' => ModelCollection::make($this->formats)->toString(),
            'is_primary' => $this->is_primary,
            'isbn10' => $this->isbn10,
            'isbn13' => $this->isbn13,
            'pages' => $this->pages,
            'release_date' => $this->formatReleaseDate(),
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->source()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'] ?? 'original';

        if (!empty($value['binding'])) {
            $item->binding = Binding::tryFromString($value['binding']);
        }
        if (!empty($value['formats'])) {
            foreach ($value['formats'] as $formatData) {
                $format = SourceEditionFormat::fromInternalJson($formatData, $item);
                $item->formats()->save($format);
            }
        }

        $item->is_primary = $value['isPrimary'] ?? false;
        $item->isbn10 = $value['isbn10'] ?? null;
        $item->isbn13 = $value['isbn13'] ?? null;
        $item->pages = $value['pages'] ?? null;
        $item->release_date = new Carbon($value['releaseDate']) ?? null;
        $item->release_date_month_only = $value['releaseDateMonthOnly'] ?? false;

        $item->save();
        return $item;
    }

    public static function fromFeJson(array $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->source()->associate($parent);

        $item->release_date = new Carbon($value['published']);
        $item->is_primary = true;
        $item->name = 'original';
        $item->save();

        foreach ($value['contents'] as $contentsData) {
            $sourceContents = SourceContents::fromFeJson($contentsData, $item);
            $item->contents()->save($sourceContents);
        }

        $item->save();
        return $item;
    }

    /**
     * @param Source $parent
     */
    public static function fromFeJsonExtra(array|string $value, ModelInterface $parent = null): ?static
    {
        $item = $parent->editions()->where('name', $value['name'])->first();

        if (empty($item)) {
            return null;
        }

        // Binding.
        if (!empty($value['binding'])) {
            $item->binding = Binding::tryFromString($value['binding']);
        }
        // Formats.
        foreach ($value['formats'] ?? [] as $formatData) {
            $format = SourceEditionFormat::fromInternalJson($formatData, $item);
            $item->formats()->save($format);
        }
        // ISBNs & pages.
        $item->isbn10 = $value['isbn10'] ?? null;
        $item->isbn13 = $value['isbn13'] ?? null;
        $item->pages = $value['pages'] ?? null;
        $item->save();
        return $item;
    }
}
