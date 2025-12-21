<?php

declare(strict_types=1);

namespace App\DTOs\Media;

use App\DTOs\AbstractDTO;
use App\Models\Media;
use App\Models\ModelInterface;

readonly class MediaSummaryDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $url
    ) {
    }

    /**
     * @param Media $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            url: $model->getUrl()
        );
    }
}
