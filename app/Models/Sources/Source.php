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
use App\Models\Media\Media;
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

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?CampaignSetting $campaign_setting
 * @property ?Media $coverImage
 * @property ?string $description
 * @property Collection<SourceEdition> $editions
 * @property ?Source $parent
 * @property Uuid $parent_id
 * @property ?SourceEdition $primaryEdition
 * @property PublicationType $publication_type
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
        'gameEdition' => GameEdition::class,
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

    public function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->primaryEdition->coverImage
        );
    }

    public function editions(): HasMany
    {
        return $this->hasMany(SourceEdition::class);
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->primaryEdition->game_edition,
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

    protected function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => PublicationType::tryFrom($value)?->toString() ?? null,
        );
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

        if (!empty($value['campaignSetting'])) {
            $campaignSetting = CampaignSetting::query()->where('slug', $value['campaignSetting'])->firstOrFail();
            $item->campaignSetting()->associate($campaignSetting);
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
    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
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
        $isWizards = str_contains(mb_strtolower($value['author'] ?? 'wizards'), 'wizards');

        $item->publication_type = $isWizards ? PublicationType::OFFICIAL : PublicationType::THIRD_PARTY;
        $item->source_type = SourceType::SOURCEBOOK;

        // Is it an adventure?
        if (!empty($value['isAdventure'])) {
            $sourceSourcebookType = SourceSourcebookType::fromInternalJson('adventure', $item);
            $item->sourcebookTypes()->save($sourceSourcebookType);
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
        foreach ($value['productIds'] ?? [] as $key => $id) {
            ProductId::fromInternalJson([
                'company' => $key,
                'productId' => $id,
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
