<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureMainTypeEdition;
use App\Models\ModelInterface;

readonly class CreatureMainTypeEditionDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?string $alternateName = null,
        public string $description,
        public string $gameEdition
    ) {
    }

    /**
     * @param CreatureMainTypeEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            alternateName: $model->alternate_name,
            description: $model->description,
            gameEdition: $model->game_edition,
        );
    }
}
