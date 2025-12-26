<?php

namespace App\Models\Sources;

use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Enums\PublicationType;
use App\Enums\Sources\SourceType;
use App\Exceptions\DuplicateRecordException;
use App\Models\AbstractModel;
use App\Models\CampaignSetting;
use App\Models\Company;
use App\Models\Media;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\ProductId;
use App\Models\Spells\Spell;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?CampaignSetting $campaign_setting
 * @property ?Uuid $campaign_setting_id
 * @property ?Uuid $cover_image_id
 * @property ?Media $coverImage
 * @property ?string $description
 * @property Collection<SourceEdition> $editions
 * @property ?GameEdition $game_edition
 * @property ?Source $parent
 * @property Uuid $parent_id
 * @property ?SourceEdition $primaryEdition
 * @property ?string $product_code
 * @property Collection $productIds
 * @property PublicationType $publication_type
 * @property Company $publisher
 * @property string $publisher_id
 * @property ?string $shortName
 * @property SourceType $source_type
 * @property SourceSourcebookType[] $sourcebookTypes
 */
class Source extends AbstractModel
{
    use HasUuids;
    use Searchable;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'publication_type' => PublicationType::class,
        'source_type' => SourceType::class,
    ];

    public function campaignSetting(): BelongsTo
    {
        return $this->belongsTo(CampaignSetting::class, 'campaign_setting_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Source::class, 'parent_id');
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value = '') => app(MarkdownRenderer::class)->toHtml($value ?? ''),
        );
    }

    public function editions(): HasMany
    {
        return $this->hasMany(SourceEdition::class);
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value)?->toString(true),
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'parent_id');
    }

    public function primaryEdition(): ?SourceEdition
    {
        // If we can't find a primary edition, return the first edition. Fail only if there are no editions.
        /** @var ?SourceEdition $primary */
        $primary = $this->editions->where('is_primary', '=', true)->first();
        /** @var ?SourceEdition $first */
        $first = $this->editions->first();
        return $primary ?? $first ?? null;
    }

    public function productIds(): HasMany
    {
        return $this->hasMany(ProductId::class);
    }

    protected function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => PublicationType::tryFrom($value)?->toString() ?? null,
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisher_id');
    }

    public function sourcebookTypes(): HasMany
    {
        return $this->hasMany(SourceSourcebookType::class);
    }

    protected function sourceType(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => SourceType::tryFrom($value)?->toString() ?? null,
        );
    }

    public function spells(): MorphToMany
    {
        return $this->morphedByMany(Spell::class, 'entity');
    }

    public function toArrayFull(): array
    {
        $output = [
            'campaignSetting' => $this->campaign_setting?->toArray($this->renderMode) ?? null,
            'description' => $this->description,
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
            'productCode' => $this->product_code,
            'productIds' => $this->productIds->collect()->toArray(),
            'publicationType' => $this->publication_type,
            'publisher' => $this->publisher?->toArray(JsonRenderMode::TEASER),
            'sourceType' => $this->source_type,
        ];

        if ($this->sourcebookTypes()->count() > 0) {
            $output['sourcebookTypes'] = [];

            foreach ($this->sourcebookTypes as $sourcebookType) {
                $output['sourcebookTypes'][] = $sourcebookType->sourcebook_type;
            }
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        $output = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'shortName' => $this->shortName,
        ];

        if ($this->children->count() > 0) {
            foreach ($this->children as $child) {
                $output['children'][] = $child->toArrayShort();
            }
        }

        return $output;
    }

    public function toArrayTeaser(): array
    {
        return [
            'coverImage' => $this->coverImage?->toArray($this->renderMode),
            'gameEdition' => $this->game_edition,
            'parentId' => $this->parent_id,
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'shortName' => $this->shortName,
            'description' => $this->description,
            'product_code' => $this->product_code,
            'publication_type' => $this->publication_type,
            'source_type' => $this->source_type,
        ];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        $item->description = $value['description'] ?? null;
        $item->shortName = $value['shortName'] ?? null;
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->product_code = $value['productCode'] ?? null;

        if (!empty($value['campaignSetting'])) {
            $campaignSetting = CampaignSetting::query()->where('slug', $value['campaignSetting'])->firstOrFail();
            $item->campaignSetting()->associate($campaignSetting);
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

        $item->publication_type = PublicationType::tryFromString($value['publicationType']);

        foreach ($value['sourcebookTypes'] ?? [] as $sourcebookType) {
            $sst = SourceSourcebookType::fromInternalJson($sourcebookType, $item);
            $item->sourcebookTypes()->save($sst);
        }

        $item->source_type = SourceType::tryFromString($value['sourceType']);
        $item->save();

        foreach ($value['editions'] ?? [] as $edition) {
            $edition = SourceEdition::fromInternalJson($edition, $item);
            $item->editions()->save($edition);
        }

        if ($item->editions->count() === 0) {
            throw new \Exception("Source {$item->name} has no editions.");
        }
        if ($item->editions->count() === 1) {
            // Make sure that at least one edition is the primary one.
            /** @var SourceEdition $first */
            $first = $item->editions->first();
            $first->is_primary = true;
            $first->save();
        }

        $item->save();
        return $item;
    }

    /**
     * @throws DuplicateRecordException
     */
    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $existing = static::query()->where('name', $value['name'])->first();

        if (!empty($existing)) {
            throw new DuplicateRecordException("Source {$value['name']} already exists.");
        }

        $item = new static();
        $item->id = Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = static::makeSlug($value['name']);
        $item->shortName = !empty($value['isAdventure']) ? $value['id'] : $value['source'];
        // Not a great way to determine official-ness. If the author field is missing, or if it contains "wizards", we
        // will assume it's official.
        $item->publication_type = str_contains(mb_strtolower($value['author'] ?? 'wizards'), 'wizards') ?
            PublicationType::OFFICIAL :
            PublicationType::THIRD_PARTY;
        $item->source_type = SourceType::SOURCEBOOK;

        // Is it an adventure?
        if (!empty($value['isAdventure'])) {
            $sourceSourcebookType = SourceSourcebookType::fromInternalJson('adventure', $item);
            $item->sourcebookTypes()->save($sourceSourcebookType);
        }

        // Work out if it's 5e 2014 or 5e 2024.
        $fifthDate = Carbon::parse('2024-09-17');
        $myDate = Carbon::parse($value['published']);

        if (str_contains($item->name, '2014') || $myDate < $fifthDate) {
            $item->game_edition = GameEdition::FIFTH;
        } else {
            $item->game_edition = GameEdition::FIFTH_REVISED;
        }

        $edition = SourceEdition::from5eJson($value, $item);
        $item->editions()->save($edition);
        $item->save();
        return $item;
    }

    public static function fromFeJsonExtra(array|string $value, ModelInterface $parent = null): ?static
    {
        $item = Source::query()->where('name', $value['name'])->first();

        if (empty($item)) {
            return null;
        }
        // Campaign setting.
        if (!empty($value['campaignSetting'])) {
            $campaignSetting = CampaignSetting::query()->where('slug', $value['campaignSetting'])->firstOrFail();
            $item->campaignSetting()->associate($campaignSetting);
        }
        // Description.
        if (!empty($value['description'])) {
            $item->description = $value['description'];
        }
        // Editions.
        foreach ($value['editions'] ?? [] as $edition) {
            SourceEdition::fromFeJsonExtra($edition, $item);
        }

        // Product IDs.
        foreach ($value['productIds'] ?? [] as $key => $value) {
            ProductId::fromInternalJson([
                'company' => $key,
                'productId' => $value,
            ], $item);
        }

        // Sourcebook types.
        foreach ($value['sourcebookTypes'] ?? [] as $sourcebookType) {
            SourceSourcebookType::fromInternalJson($sourcebookType, $item);
        }

        // Cover image.
        if (!empty($value['coverImage'])) {
            $coverImage = Media::fromInternalJson([
                'filename' => '/books/' . $value['coverImage'],
            ]);
            $item->coverImage()->associate($coverImage);
        }

        $item->save();
        return $item;
    }
}
