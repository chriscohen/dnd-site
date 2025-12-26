<?php

declare(strict_types=1);

namespace App\DTOs\Spells;

use App\DTOs\AbstractDTO;
use App\DTOs\CharacterClasses\CharacterClassSummaryDTO;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\ModelInterface;
use App\Models\Spells\SpellEditionLevel;

readonly class SpellEditionLevelDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public ?CharacterClassSummaryDTO $characterClass = null,
        public int $item,
        public int $level,
    ) {
    }

    /**
     * @param SpellEditionLevel $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            characterClass: $model->entity instanceof CharacterClass::class ?
                CharacterClassSummaryDTO::fromModel($model->entity) :
                null,
            item: $model->item,
            level: $model->level,
        );
    }
}
