<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\AbstractDTO;
use App\DTOs\Media\MediaSummaryDTO;
use App\Models\ModelInterface;
use App\Models\Sources\Source;

readonly class SourceSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?MediaSummaryDTO $coverImage = null,
        public string $gameEdition,
        public string $name,
        public ?string $parentId = null,
        public ?string $shortName = null,
        public string $slug,
    ) {
    }

    /**
     * @param Source $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            coverImage: $model->coverImage ? MediaSummaryDTO::fromModel($model->coverImage) : null,
            gameEdition: $model->game_edition,
            name: $model->name,
            parentId: $model->parent_id,
            shortName: $model->shortName,
            slug: $model->slug,
        );
    }
}
