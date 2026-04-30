<?php

declare(strict_types=1);

namespace App\DTOs\AbilityScores;

use App\DTOs\AbstractDTO;
use App\Models\AbilityScores\AbilityScore;
use App\Models\ModelInterface;

readonly class AbilityScoreDTO extends AbstractDTO
{
    public function __construct(
        public bool $isProficient,
        public int $modifier,
        public string $type,
        public int $value
    ) {
    }

    /**
     * @param AbilityScore $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            isProficient: $model->is_proficient,
            modifier: $model->modifier,
            type: $model->type->toStringShort(),
            value: $model->value
        );
    }
}
