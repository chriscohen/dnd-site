<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\CampaignSettingFullDTO;
use App\DTOs\CompanySummaryDTO;
use App\DTOs\Media\MediaSummaryDTO;
use App\DTOs\ProductIdDTO;
use App\Models\ModelInterface;
use App\Models\ProductId;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use Illuminate\Support\Collection;

readonly class SourceFullDTO extends SourceSummaryDTO
{
    public function __construct(
        string $id,
        ?MediaSummaryDTO $coverImage = null,
        string $gameEdition,
        string $name,
        ?string $parentId = null,
        ?string $shortName = null,
        string $slug,
        // Summary.
        public ?CampaignSettingFullDTO $campaignSetting = null,
        public ?string $description = null,
        /** @var Collection<SourceEditionFullDTO> */
        public Collection $editions,
        public ?string $productCode = null,
        /** @var Collection<ProductIdDTO> */
        public Collection $productIds,
        public string $publicationType,
        public CompanySummaryDTO $publisher,
        public string $sourceType
    ) {
        parent::__construct($id, $coverImage, $gameEdition, $name, $parentId, $shortName, $slug);
    }

    /**
     * @param Source $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            coverImage: $model->coverImage ? MediaSummaryDTO::fromModel($model->coverImage) : null,
            gameEdition: $model->game_edition,
            name: $model->name,
            parentId: $model->parent_id,
            shortName: $model->shortName,
            slug: $model->slug,
            // Summary.
            campaignSetting: $model->campaignSetting ?
                CampaignSettingFullDTO::fromModel($model->campaignSetting) :
                null,
            description: $model->description,
            editions: $model->relationLoaded('editions') ?
                $model->editions->map(fn (SourceEdition $item) => SourceEditionFullDTO::fromModel($item)) :
                [],
            productCode: $model->product_code,
            productIds: $model->relationLoaded('productIds') ?
                $model->productIds->map(fn (ProductId $item) => $item) :
                collect(),
            publicationType: $model->publication_type,
            publisher: CompanySummaryDTO::fromModel($model->publisher),
            sourceType: $model->source_type
        );
    }
}
