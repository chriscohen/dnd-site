<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureAlignment;
use App\Models\ModelInterface;

readonly class CreatureAlignmentDTO extends AbstractDTO
{
    public function __construct(
        public string $alignment
    ) {
    }

    /**
     * @param CreatureAlignment $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            alignment: $model->alignment->toString()
        );
    }
}
