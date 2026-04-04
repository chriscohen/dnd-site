<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\ModelInterface;
use App\Models\ProductId;

readonly class ProductIdDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public CompanySummaryDTO $company,
        public string $productId,
        public ?string $url = null
    ) {
    }

    /**
     * @param ProductId $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            company: CompanySummaryDTO::fromModel($model->origin),
            productId: $model->product_id,
            url: $model->getUrl()
        );
    }
}
