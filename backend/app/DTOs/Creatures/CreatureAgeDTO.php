<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Enums\Creatures\CreatureAgeType;
use App\Models\Creatures\CreatureAge;
use App\Models\ModelInterface;

readonly class CreatureAgeDTO extends AbstractDTO
{
    public function __construct(
        public readonly CreatureAgeType $type,
        public readonly int $value,
    ) {
    }

    /**
     * @param CreatureAge $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            type: $model->type,
            value: $model->value
        );
    }
}
