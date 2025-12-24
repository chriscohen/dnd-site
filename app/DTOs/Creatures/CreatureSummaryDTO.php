<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;

readonly class CreatureSummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug
        );
    }
}
