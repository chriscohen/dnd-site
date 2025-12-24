<?php

declare(strict_types=1);

namespace App\DTOs\ArmorClass;

use App\DTOs\AbstractDTO;
use App\Enums\ArmorClass\ArmorClassSource;
use Ramsey\Uuid\Uuid;

readonly class ArmorClassItemDTO extends AbstractDTO
{
    public function __construct(
        public readonly Uuid $id,
        public readonly ArmorClassSource $sourceType,
        public readonly int $value
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            sourceType: $model->source_type,
            value: $model->value
        );
    }
}
