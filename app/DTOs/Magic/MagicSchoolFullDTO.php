<?php

declare(strict_types=1);

namespace App\DTOs\Magic;

use App\DTOs\Media\MediaSummaryDTO;

readonly class MagicSchoolFullDTO extends MagicSchoolSummaryDTO
{
    public function __construct(
        string $id,
        string $name,
        // Summary.
        public ?string $description = null,
        public ?MediaSummaryDTO $image = null,
        public ?MagicSchoolSummaryDTO $parent = null,
        public ?string $shortName = null
    ) {
        parent::__construct($id, $name);
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            // Summary.
            description: $model->description,
            image: !empty($model->image) ? MediaSummaryDTO::fromModel($model->image) : null,
            parent: $model->parent ? MagicSchoolSummaryDTO::fromModel($model->parent) : null,
            shortName: $model->short_name
        );
    }
}
