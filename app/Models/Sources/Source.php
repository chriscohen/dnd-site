<?php

namespace App\Models\Sources;

use App\Enums\GameEdition;
use App\Enums\PublicationType;
use App\Enums\SourceType;
use App\Models\AbstractModel;
use App\Models\CampaignSetting;
use App\Models\Company;
use App\Models\Media;
use App\Models\ModelCollection;
use App\Models\ProductId;
use App\Models\Spells\Spell;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?CampaignSetting $campaignSetting
 * @property ?Uuid $campaignSettingId
 * @property ?Uuid $coverImageId
 * @property ?Media $coverImage
 * @property ?string $description
 * @property Collection<SourceEdition> $editions
 * @property ?GameEdition $gameEdition
 * @property ?Source $parent
 * @property Uuid $parentId
 * @property ?string $productCode
 * @property Collection $productIds
 * @property PublicationType $publicationType
 * @property Company $publisher
 * @property string $publisherId
 * @property ?string $shortName
 * @property SourceType $sourceType
 * @property SourceSourcebookType[] $sourcebookTypes
 */
class Source extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
        'publicationType' => PublicationType::class,
        'sourceType' => SourceType::class,
    ];

    public function campaignSetting(): BelongsTo
    {
        return $this->belongsTo(CampaignSetting::class, 'campaignSettingId');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Source::class, 'parentId');
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'coverImageId');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->toHtml($value),
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
        return $this->belongsTo(Source::class, 'parentId');
    }

    public function primaryEdition(): ?SourceEdition
    {
        /** @var SourceEdition|null $edition */
        $edition = $this->editions->where('isPrimary', true)->firstOrFail();
        return $edition;
    }

    public function productIds(): HasMany
    {
        return $this->hasMany(ProductId::class);
    }

    protected function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => PublicationType::tryFrom($value)?->toString(),
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisherId');
    }

    public function sourcebookTypes(): HasMany
    {
        return $this->hasMany(SourceSourcebookType::class);
    }

    protected function sourceType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => SourceType::tryFrom($value)?->toString(),
        );
    }

    public function spells(): MorphToMany
    {
        return $this->morphedByMany(Spell::class, 'entity');
    }

    public function toArrayFull(): array
    {
        $output = [
            'campaignSetting' => $this->campaignSetting?->toArray($this->renderMode) ?? null,
            'description' => $this->description,
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
            'gameEdition' => $this->gameEdition,
            'productCode' => $this->productCode,
            'productIds' => $this->productIds->collect()->toArray(),
            'publicationType' => $this->publicationType,
            'publisher' => $this->publisher->toArray($this->renderMode),
            'sourceType' => $this->sourceType,
        ];

        if ($this->sourcebookTypes()->count() > 0) {
            $output['sourcebookTypes'] = [];

            foreach ($this->sourcebookTypes as $sourcebookType) {
                $output['sourcebookTypes'][] = $sourcebookType->sourcebookType;
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
            'coverImage' => $this->coverImage->toArray($this->renderMode),
            'parentId' => $this->parentId,
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
