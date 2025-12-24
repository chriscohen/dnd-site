<?php

declare(strict_types=1);

namespace App\DTOs\StatusConditions;

use App\DTOs\AbstractDTO;

readonly class StatusConditionEditionDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $gameEdition,
        public readonly ?string $description = null,
        /** @var string[]|null $rules */
        public readonly ?array $rules = null
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            gameEdition: $model->game_edition,
            description: $model->description,
            rules: $model->relationLoaded('rules') ? $model->rules->pluck('rule')->toArray() : null
        );
    }
}
