<?php

declare(strict_types=1);

namespace App\DTOs\StatusConditions;

use App\DTOs\AbstractDTO;
use App\Models\ModelInterface;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Support\Collection;

readonly class StatusConditionDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly Collection $editions
    ) {
    }

    /**
     * @param StatusCondition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            editions: $model->relationLoaded('editions') ?
                $model->editions->map(
                    fn (StatusConditionEdition $item) => StatusConditionEditionDTO::fromModel($item)
                ) :
                collect()
        );
    }
}
