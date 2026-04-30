<?php

declare(strict_types=1);

namespace App\DTOs;

use App\DTOs\Media\MediaSummaryDTO;
use App\Enums\PublicationType;
use App\Models\CampaignSetting;
use App\Models\ModelInterface;

readonly class CampaignSettingFullDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description = null,
        public readonly ?MediaSummaryDTO $logo = null,
        public readonly ?string $publicationType = null,
        public readonly CompanySummaryDTO $publisher,
        public readonly ?int $startYear = null,
    ) {
    }

    /**
     * @param CampaignSetting $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            description: $model->description,
            logo: $model->logo ? MediaSummaryDTO::fromModel($model->logo) : null,
            publicationType: $model->publication_type,
            publisher: $model->publisher ? CompanySummaryDTO::fromModel($model->publisher) : null,
            startYear: $model->start_year,
        );
    }
}
