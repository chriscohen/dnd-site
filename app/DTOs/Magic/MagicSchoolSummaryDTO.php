<?php

declare(strict_types=1);

namespace App\DTOs\Magic;

use App\DTOs\AbstractDTO;

readonly class MagicSchoolSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
        );
    }
}
