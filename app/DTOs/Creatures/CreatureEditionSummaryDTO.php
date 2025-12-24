<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use Ramsey\Uuid\Uuid;

readonly class CreatureEditionSummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id
    ) {
    }

    /**
     * @param CreatureEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id
        );
    }
}
