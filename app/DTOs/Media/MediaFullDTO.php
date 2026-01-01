<?php

declare(strict_types=1);

namespace App\DTOs\Media;

use App\Models\Media\Media;
use App\Models\ModelInterface;
use Illuminate\Support\Collection;

readonly class MediaFullDTO extends MediaSummaryDTO
{
    public function __construct(
        string $url,
        public ?string $name = null,
        public ?string $filename = null,
        public string $mediaType,
        public ?string $mimeType = null,
        public ?string $collection = null,
        public ?int $size = null
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
            mediaType: $model->media_type->toString(),
            mimeType: $model->mime_type,
            collection: $model->collection_name,
            size: $model->size
        );
    }
}
