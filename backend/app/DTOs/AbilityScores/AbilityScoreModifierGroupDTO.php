<?php

declare(strict_types=1);

namespace App\DTOs\AbilityScores;

use App\DTOs\AbstractDTO;
use App\Models\AbilityScores\AbilityScoreModifier;
use App\Models\AbilityScores\AbilityScoreModifierGroup;
use App\Models\ModelInterface;
use Illuminate\Support\Collection;

readonly class AbilityScoreModifierGroupDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        /** @var Collection<AbilityScoreModifierDTO> $modifiers */
        public readonly Collection $modifiers,
        public readonly ?int $choiceCount = null,
        public readonly ?string $choices = null,
        public readonly ?bool $hasChoice = false,
        public readonly ?int $str = null,
        public readonly ?int $dex = null,
        public readonly ?int $con = null,
        public readonly ?int $int = null,
        public readonly ?int $wis = null,
        public readonly ?int $cha = null
    ) {
    }

    /**
     * @param AbilityScoreModifierGroup $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            modifiers: $model->relationLoaded('modifiers') ?
                $model->modifiers->map(fn (AbilityScoreModifier $item) => AbilityScoreModifierDTO::fromModel($item)) :
                collect(),
            choiceCount: $model->choice_count,
            choices: $model->choices,
            hasChoice: $model->has_choice,
            str: $model->str,
            dex: $model->dex,
            con: $model->con,
            int: $model->int,
            wis: $model->wis,
            cha: $model->cha
        );
    }
}
