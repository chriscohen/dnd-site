<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureMajorType;
use App\Models\ModelInterface;

readonly class CreatureMajorTypeSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $plural,
        public string $slug
    ) {
    }

    /**
     * @param CreatureMajorType $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            plural: $model->plural,
            slug: $model->slug
        );
    }
}
