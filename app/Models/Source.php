<?php

namespace App\Models;

use App\Enums\GameEdition;
use App\Enums\PublicationType;
use App\Enums\SourceType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?CampaignSetting $campaign_setting
 * @property ?Uuid $campaign_setting_id
 * @property string $cover_image
 * @property ?string $description
 * @property Collection<SourceEdition> $editions
 * @property ?GameEdition $game_edition
 * @property string $name
 * @property ?string $product_code
 * @property PublicationType $publication_type
 * @property string $publisher_id
 * @property SourceType $source_type
 */
class Source extends AbstractModel implements HasMedia
{
    use InteractsWithMedia;
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
}
