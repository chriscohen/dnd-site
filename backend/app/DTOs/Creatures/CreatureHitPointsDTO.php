<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureHitPoints;
use App\Models\ModelInterface;

readonly class CreatureHitPointsDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?int $average = null,
        public ?string $description = null,
        public ?string $formula = null
    ) {
    }

    /**
     * @param CreatureHitPoints $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            average: $model->average,
            description: $model->description,
            formula: $model->formula?->toString()
        );
    }
}
