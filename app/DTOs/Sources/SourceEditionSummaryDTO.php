<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\AbstractDTO;
use App\Models\ModelInterface;
use App\Models\Sources\SourceEdition;

readonly class SourceEditionSummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {
    }

    /**
     * @param SourceEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name
        );
    }
}
