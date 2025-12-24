<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbilityScores\AbilityScoreModifierGroupDTO;
use App\DTOs\ArmorClass\ArmorClassDTO;
use App\Models\Creatures\CreatureAge;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use App\Models\StatusConditions\StatusConditionEdition;
use Illuminate\Support\Collection;

readonly class CreatureEditionFullDTO extends CreatureEditionSummaryDTO
{
    public function __construct(
        string $id,
        public readonly ?AbilityScoreModifierGroupDTO $abilityScoreModifiers = null,
        /** @var Collection<CreatureAgeDTO> $ages */
        public readonly ?Collection $ages = null,
        public readonly ?ArmorClassDTO $armorClass = null,
        public readonly ?int $challengeRating = null,
        /** @var string[] $conditionImmune */
        public readonly array $conditionImmune,
        /** @var string[] $immune */
        public readonly array $immune,
        public readonly array $resist
    ) {
        parent::__construct($id);
    }

    /**
     * @param CreatureEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            abilityScoreModifiers: $model->abilityScoreModifiers ?
                AbilityScoreModifierGroupDTO::fromModel($model->abilityScoreModifiers) :
                null,
            ages: $model->relationLoaded('ages') ?
                $model->ages->map(fn (CreatureAge $item) => CreatureAgeDTO::fromModel($item)) :
                collect(),
            armorClass: $model->armorClass ? ArmorClassDTO::fromModel($model->armorClass) : null,
            challengeRating: $model->challenge_rating,
            conditionImmune: $model->relationLoaded('conditionImmunities') ?
                $model->conditionImmunities->map(
                    fn (StatusConditionEdition $item) => $item->statusCondition->slug
                ) :
                [],
            immune: $model->damage_immunities->toArray(),
            resist: $model->damage_resistances->toArray()
        );
    }
}
