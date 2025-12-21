<?php

declare(strict_types=1);

namespace App\DTOs;

use App\DTOs\Media\MediaSummaryDTO;
use App\DTOs\Sources\SourceSummaryDTO;
use App\Models\Company;
use App\Models\Media;
use App\Models\ModelInterface;
use App\Models\Sources\Source;
use Illuminate\Database\Eloquent\Collection;

readonly class CompanyFullDTO extends CompanySummaryDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?Media $logo = null,
        public readonly ?string $productUrl = null,
        public readonly ?string $shortName = null,
        public readonly ?string $website = null,
        public readonly ?Collection $products = null
    ) {
        parent::__construct($id, $name, $slug, $logo, $productUrl, $shortName);
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
            logo: $model->logo ? MediaSummaryDTO::fromModel($model->logo) : null,
            productUrl: $model->product_url,
            shortName: $model->short_name,
            products: $model->relationLoaded('products') ?
                $model->products->map(fn (Source $item) => SourceSummaryDTO::fromModel($item)) :
                []
        );
    }
}
