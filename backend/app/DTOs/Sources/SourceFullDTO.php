<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\CampaignSettingFullDTO;
use App\DTOs\Media\MediaSummaryDTO;
use App\Models\ModelInterface;
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
        public string $publicationType,
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
            gameEdition: $model->primaryEdition->game_edition->toStringShort(),
            name: $model->name,
            parentId: $model->parent_id,
            shortName: $model->short_name,
            slug: $model->slug,
            // Summary.
            campaignSetting: $model->campaignSetting ?
                CampaignSettingFullDTO::fromModel($model->campaignSetting) :
                null,
            description: $model->description,
            editions: $model->relationLoaded('editions') ?
                $model->editions->map(fn (SourceEdition $item) => SourceEditionFullDTO::fromModel($item)) :
                [],
            publicationType: $model->publication_type,
            sourceType: $model->source_type
        );
    }
}
