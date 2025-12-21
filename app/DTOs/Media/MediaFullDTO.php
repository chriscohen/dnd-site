<?php

declare(strict_types=1);

namespace App\DTOs\Media;

use App\DTOs\AbstractDTO;
use App\Models\Media;
use App\Models\ModelInterface;

readonly class MediaFullDTO extends MediaSummaryDTO
{
    public function __construct(
        string $url,
        public readonly ?string $name = null,
        public readonly ?string $filename = null,
        public readonly ?string $mimeType = null,
        public readonly ?string $collection = null,
        public readonly ?int $size = null
    ) {
        parent::__construct($url);
    }

    /**
     * @param Media $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            url: $model->getUrl(),
            name: $model->name,
            filename: $model->filename,
            mimeType: $model->mime_type,
            collection: $model->collection_name,
            size: $model->size
        );
    }
}
