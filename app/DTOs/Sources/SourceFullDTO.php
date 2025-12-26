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
        string $name,
        string $slug,
        string $gameEdition,
        ?string $shortName = null,
        ?MediaSummaryDTO $coverImage = null,
        $parentId = null,
        public ?CampaignSettingFullDTO $campaignSetting = null,
        public ?string $description = null,
        /** @var Collection<SourceEditionFullDTO> */
        public Collection $editions
    ) {
        parent::__construct($id, $name, $slug, $gameEdition, $shortName, $coverImage, $parentId);
    }

    /**
     * @param Source $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            gameEdition: $model->game_edition,
            shortName: $model->shortName,
            coverImage: $model->coverImage ? MediaSummaryDTO::fromModel($model->coverImage) : null,
            parentId: $model->parent_id,
            campaignSetting: $model->campaignSetting ?
                CampaignSettingFullDTO::fromModel($model->campaignSetting) :
                null,
            description: $model->description,
            editions: $model->relationLoaded('editions') ?
                $model->editions->map(fn (SourceEdition $item) => SourceEditionFullDTO::fromModel($item)) :
                []
        );
    }
}
