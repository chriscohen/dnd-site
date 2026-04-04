<?php

namespace App\DTOs\CharacterClasses;

use App\DTOs\AbstractDTO;
use App\DTOs\Media\MediaSummaryDTO;

readonly class CharacterClassSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug
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
