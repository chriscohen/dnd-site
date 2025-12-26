<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureMajorTypeEdition;
use App\Models\ModelInterface;

readonly class CreatureMajorTypeEditionDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?string $alternateName = null,
        public string $description,
        public string $gameEdition
    ) {
    }

    /**
     * @param CreatureMajorTypeEdition $model
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
