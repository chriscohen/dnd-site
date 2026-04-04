<?php

declare(strict_types=1);

namespace App\DTOs\AbilityScores;

use App\DTOs\AbstractDTO;

readonly class AbilityScoreModifierDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $abilityScore,
        public readonly int $value
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            abilityScore: $model->ability_score,
            value: $model->value
        );
    }
}
