<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\ModelInterface;

readonly class SearchResultDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $type,
        public string $slug,
        public string $name
    ) {
    }

    public static function fromModel(ModelInterface $model): static
    {
        $pieces = explode('\\', $model::class);
        return new static(
            id: $model->id,
            type: mb_strtolower(end($pieces)),
            slug: $model->slug,
            name: $model->name
        );
    }
}
