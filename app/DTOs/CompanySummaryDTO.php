<?php

declare(strict_types=1);

namespace App\DTOs;

use App\DTOs\Media\MediaSummaryDTO;
use App\Models\Company;
use App\Models\ModelInterface;

readonly class CompanySummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public ?MediaSummaryDTO $logo = null,
        public ?string $productUrl = null,
        public ?string $shortName = null,
        public ?string $website = null
    ) {
    }

    /**
     * @param Company $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            logo: $model->logo,
            productUrl: $model->product_url,
            shortName: $model->short_name,
        );
    }
}
