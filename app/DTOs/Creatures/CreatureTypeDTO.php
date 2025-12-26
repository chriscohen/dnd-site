<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureType;
use App\Models\ModelInterface;

readonly class CreatureTypeDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $gameEdition,
        public ?CreatureMajorTypeSummaryDTO $majorType = null,
        public ?CreatureOriginDTO $origin = null
    ) {
    }

    /**
     * @param CreatureType $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            gameEdition: $model->game_edition->toStringShort(),
            majorType: $model->relationLoaded('majorType') ?
                CreatureMajorTypeSummaryDTO::fromModel($model->majorType) :
                null,
            origin: $model->relationLoaded('origin') && !empty($model->origin) ?
                CreatureOriginDTO::fromModel($model->origin) :
                null
        );
    }
}
