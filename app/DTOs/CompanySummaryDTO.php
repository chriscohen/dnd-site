<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Company;
use App\Models\Media;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class CompanySummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?Media $logo = null,
        public readonly ?string $productUrl = null,
        public readonly ?string $shortName = null,
        public readonly ?string $website = null
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
