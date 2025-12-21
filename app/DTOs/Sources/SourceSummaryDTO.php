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
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $gameEdition,
        public readonly ?string $shortName = null,
        public readonly ?MediaSummaryDTO $coverImage = null,
        public readonly ?string $parentId = null,
    ) {
    }

    /**
     * @param Source $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            shortName: $model->shortName,
            coverImage: $model->coverImage ? MediaSummaryDTO::fromModel($model->coverImage) : null,
            gameEdition: $model->game_edition,
            parentId: $model->parent_id,
        );
    }
}
