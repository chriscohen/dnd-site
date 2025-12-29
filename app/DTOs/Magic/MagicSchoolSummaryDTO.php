<?php

declare(strict_types=1);

namespace App\DTOs\Magic;

use App\DTOs\AbstractDTO;
use App\Models\Magic\MagicSchool;
use App\Models\ModelInterface;

readonly class MagicSchoolSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }

    /**
     * @param MagicSchool $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
        );
    }
}
