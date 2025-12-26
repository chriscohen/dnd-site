<?php

declare(strict_types=1);

namespace App\DTOs\MovementSpeeds;

use App\DTOs\AbstractDTO;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeed;

readonly class MovementSpeedDTO extends AbstractDTO
{
    public function __construct(
        public bool $canHover,
        public string $type,
        public int $value
    ) {
    }

    /**
     * @param MovementSpeed $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            canHover: $model->can_hover,
            type: $model->type->toString(),
            value: $model->value
        );
    }
}
