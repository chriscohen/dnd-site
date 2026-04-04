<?php

declare(strict_types=1);

namespace App\DTOs\ArmorClass;

use App\DTOs\AbstractDTO;
use App\Enums\ArmorClass\ArmorClassSource;
use App\Models\ArmorClass\ArmorClassItem;
use App\Models\ModelInterface;

readonly class ArmorClassItemDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $sourceType,
        public readonly int $value
    ) {
    }

    /**
     * @param ArmorClassItem $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            sourceType: $model->source_type->toString(),
            value: $model->value
        );
    }
}
