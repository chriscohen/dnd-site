<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureOrigin;
use App\Models\ModelInterface;

readonly class CreatureOriginDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $origin,
        public string $plural
    ) {
    }

    /**
     * @param CreatureOrigin $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            origin: $model->origin,
            plural: $model->plural,
        );
    }
}
