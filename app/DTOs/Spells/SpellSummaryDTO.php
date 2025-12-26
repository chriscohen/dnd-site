<?php

declare(strict_types=1);

namespace App\DTOs\Spells;

use App\DTOs\AbstractDTO;
use App\DTOs\Media\MediaSummaryDTO;

readonly class SpellSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?MediaSummaryDTO $image = null,
        public string $name,
        public string $slug
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            image: !empty($model->image) ? MediaSummaryDTO::fromModel($model->image) : null,
            name: $model->name,
            slug: $model->slug
        );
    }
}
