<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\AbstractDTO;
use App\Enums\Sources\SourceContentsType;
use App\Models\ModelInterface;
use App\Models\Sources\SourceContents;
use App\Models\Sources\SourceContentsHeader;

readonly class SourceContentsDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $sourceEditionId,
        public readonly string|int|null $ordinal = null,
        public readonly ?SourceContentsType $sourceContentsType = null,
        /** @var string[] $headers */
        public readonly array $headers
    ) {
    }

    /**
     * @param SourceContents $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            sourceEditionId: $model->source_edition_id,
            ordinal: $model->ordinal,
            sourceContentsType: $model->type,
            headers: $model->headers->map(fn (SourceContentsHeader $item) => $item->header)->toArray()
        );
    }
}
