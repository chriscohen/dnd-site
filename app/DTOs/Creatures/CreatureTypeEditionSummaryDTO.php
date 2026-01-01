<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\ModelInterface;

readonly class CreatureTypeEditionSummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id
    ) {
    }

    /**
     * @param CreatureTypeEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id
        );
    }
}
