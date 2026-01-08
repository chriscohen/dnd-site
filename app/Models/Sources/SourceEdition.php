<?php

namespace App\Models\Sources;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\Company;
use App\Models\Media\Media;
use App\Models\ModelInterface;
use App\Models\People\BookCredit;
use App\Models\ProductId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Binding $binding
 * @property Collection<BoxedSetItem> $boxedSetItems
 * @property Collection<SourceContents> $contents
 * @property ?Media $coverImage
 * @property Collection<BookCredit> $credits
 * @property Collection<SourceEditionFormat> $formats
 * @property GameEdition $game_edition
 * @property bool $hasContents
 * @property bool $hasCredits
 * @property bool $is_primary
 * @property ?string $isbn10
 * @property ?string $isbn13
 * @property ?int $level_end
 * @property ?int $level_start
 * @property string $name
 * @property ?int $pages
 * @property ?string $product_code
 * @property Collection<ProductId> $productIds
 * @property ?Company $publisher
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
        'game_edition' => GameEdition::class,
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

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(BookCredit::class, 'source_edition_id');
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

    public function hasContents(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contents->count() > 0,
        );
    }

    public function hasCredits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->credits->count() > 0,
        );
    }

    public function productIds(): HasMany
    {
        return $this->hasMany(ProductId::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisher_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->source()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'] ?? 'original';
        $item->product_code = $value['productCode'] ?? null;
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->save();

        if (!empty($value['binding'])) {
            $item->binding = Binding::tryFromString($value['binding']);
        }
        if (!empty($value['formats'])) {
            foreach ($value['formats'] as $formatData) {
                $format = SourceEditionFormat::fromInternalJson($formatData, $item);
                $item->formats()->save($format);
            }
        }
        if (!empty($value['coverImage'])) {
            $coverImage = Media::fromInternalJson([
                'filename' => '/books/' . $value['coverImage'],
            ]);
            $item->coverImage()->associate($coverImage);
        }
        foreach ($value['productIds'] ?? [] as $vendor => $productIdData) {
            $productId = ProductId::fromInternalJson([
                'company' => $vendor,
                'productId' => $productIdData
            ], $item);
            $item->productIds()->save($productId);
        }

        // Allow for both publisherId (uuid) or publisher (slug).
        if (!empty($value['publisherId'])) {
            $company = Company::query()->where('id', $value['publisherId'])->firstOrFail();
            $item->publisher()->associate($company);
        } elseif (!empty($value['publisher'])) {
            $company = Company::query()->where('slug', $value['publisher'])->firstOrFail();
            $item->publisher()->associate($company);
        }

        $item->is_primary = $value['isPrimary'] ?? false;
        $item->isbn10 = $value['isbn10'] ?? null;
        $item->isbn13 = $value['isbn13'] ?? null;
        if (!empty($value['level'])) {
            $item->level_start = $value['level']['start'];
            $item->level_end = $value['level']['end'] ?? null;
        }
        $item->pages = $value['pages'] ?? null;
        $item->release_date = new Carbon($value['releaseDate']) ?? null;
        $item->release_date_month_only = $value['releaseDateMonthOnly'] ?? false;

        // Contents.
        foreach ($value['contents'] ?? [] as $contentsData) {
            $sourceContents = SourceContents::fromInternalJson($contentsData, $item);
            $item->contents()->save($sourceContents);
        }

        // Credits.
        foreach ($value['credits'] ?? [] as $key => $creditData) {
            foreach ($creditData as $creditPerson) {
                $credit = BookCredit::fromInternalJson([
                    'role' => $key,
                    'person' => $creditPerson
                ], $item);
                $item->credits()->save($credit);
            }
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->source()->associate($parent);

        $item->release_date = new Carbon($value['published']);
        $item->is_primary = true;
        $item->name = 'original';

        // Levels, if adventure.
        if (!empty($value['level'])) {
            $item->level_start = $value['level']['start'];
            $item->level_end = $value['level']['end'] ?? null;
        }

        $isWizards = str_contains(mb_strtolower($value['author'] ?? 'wizards'), 'wizards');

        if ($isWizards) {
            $company = Company::query()->where('slug', 'wizards-of-the-coast')->firstOrFail();
            $item->publisher()->associate($company);
        }

        // Work out if it's 5e 2014 or 5e 2024.
        $fifthDate = Carbon::parse('2024-09-17');
        $myDate = Carbon::parse($value['published']);

        if (str_contains($item->name, '2014') || $myDate < $fifthDate) {
            $item->game_edition = GameEdition::FIFTH;
        } else {
            $item->game_edition = GameEdition::FIFTH_REVISED;
        }

        $item->save();

        foreach ($value['contents'] as $contentsData) {
            $sourceContents = SourceContents::from5eJson($contentsData, $item);
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

        // Credits.
        // Credits.
        foreach ($value['credits'] ?? [] as $key => $creditData) {
            foreach ($creditData as $creditPerson) {
                $credit = BookCredit::fromInternalJson([
                    'role' => $key,
                    'person' => $creditPerson
                ], $item);
                $item->credits()->save($credit);
            }
        }

        $item->save();
        return $item;
    }
}
