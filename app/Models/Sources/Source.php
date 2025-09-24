<?php

namespace App\Models\Sources;

use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
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
 *
 * @property ?CampaignSetting $campaign_setting
 * @property ?Uuid $campaign_setting_id
 * @property ?Uuid $cover_image_id
 * @property ?Media $coverImage
 * @property ?string $description
 * @property Collection<SourceEdition> $editions
 * @property ?GameEdition $game_edition
 * @property string $name
 * @property ?string $product_code
 * @property Collection $productIds
 * @property PublicationType $publication_type
 * @property Company $publisher
 * @property string $publisher_id
 * @property SourceType $source_type
 * @property SourceSourcebookType[] $sourcebookTypes
 */
class Source extends AbstractModel
{
    use HasUuids;

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

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
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

    public function primaryEdition(): ?SourceEdition
    {
        /** @var SourceEdition|null $edition */
        $edition = $this->editions->where('is_primary', true)->firstOrFail();
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
        return $this->belongsTo(Company::class, 'publisher_id');
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
            'campaign_setting' => $this->campaign_setting?->toArray($this->renderMode, $this->excluded) ?? null,
            'cover_image' => $this->coverImage->toArray($this->renderMode, $this->excluded),
            'description' => $this->description,
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode, $this->excluded),
            'game_edition' => $this->game_edition,
            'product_code' => $this->product_code,
            'product_ids' => $this->productIds->collect()->toArray(),
            'publication_type' => $this->publication_type,
            'publisher' => $this->publisher->toArray($this->renderMode, $this->excluded),
            'source_type' => $this->source_type,
        ];

        if ($this->sourcebookTypes()->count() > 0) {
            $output['sourcebook_types'] = [];

            foreach ($this->sourcebookTypes as $sourcebookType) {
                $output['sourcebook_types'][] = $sourcebookType->sourcebook_type;
            }
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'cover_image' => $this->coverImage->toArray($this->renderMode, $this->excluded),
        ];
    }
}
