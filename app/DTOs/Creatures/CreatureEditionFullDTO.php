<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbilityScores\AbilityScoreDTO;
use App\DTOs\AbilityScores\AbilityScoreModifierGroupDTO;
use App\DTOs\ArmorClass\ArmorClassDTO;
use App\DTOs\MovementSpeeds\MovementSpeedDTO;
use App\Enums\Creatures\CreatureSizeUnit;
use App\Models\AbilityScores\AbilityScore;
use App\Models\ArmorClass\ArmorClass;
use App\Models\Creatures\CreatureAge;
use App\Models\Creatures\CreatureAlignment;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use App\Models\Conditions\ConditionEdition;
use App\Models\MovementSpeeds\MovementSpeed;
use Illuminate\Support\Collection;

readonly class CreatureEditionFullDTO extends CreatureEditionSummaryDTO
{
    public function __construct(
        string $id,
        /** @var Collection<AbilityScoreDTO> $abilities */
        public Collection $abilities,
        public ?AbilityScoreModifierGroupDTO $abilityScoreModifiers = null,
        /** @var Collection<CreatureAgeDTO> $ages */
        public ?Collection $ages = null,
        /** @var Collection<CreatureAlignmentDTO> $alignment */
        public Collection $alignment,
        /** @var Collection<ArmorClassDTO> $armorClass */
        public ?Collection $armorClass = null,
        public ?float $challengeRating = null,
        /** @var string[] $conditionImmune */
        public array $conditionImmune,
        public string $gameEdition,
        public ?int $hitDieFaces = null,
        public ?CreatureHitPointsDTO $hitPoints = null,
        /** @var string[] $immune */
        public array $immune,
        public bool $isPlayable,
        public ?int $lairXp,
        /** @var Collection<MovementSpeedDTO> $movementSpeeds */
        public Collection $movementSpeeds,
        public array $resist,
        public array $sizes,
        public ?CreatureTypeDTO $type = null
    ) {
        parent::__construct($id);
    }

    /**
     * @param CreatureEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        $x = $model->alignment;
        return new static(
            id: $model->id,
            abilities: $model->relationLoaded('abilities') ?
                $model->abilities->map(fn (AbilityScore $item) => AbilityScoreDTO::fromModel($item)) : [],
            abilityScoreModifiers: $model->abilityScoreModifiers ?
                AbilityScoreModifierGroupDTO::fromModel($model->abilityScoreModifiers) :
                null,
            ages: $model->relationLoaded('ages') ?
                $model->ages->map(fn (CreatureAge $item) => CreatureAgeDTO::fromModel($item)) :
                collect(),
            alignment: $model->alignment?->map(
                fn (CreatureAlignment $item) => CreatureAlignmentDTO::fromModel($item)
            ) ?? collect(),
            armorClass: $model->relationLoaded('armorClass') ?
                $model->armorClass->map(fn (ArmorClass $item) => ArmorClassDTO::fromModel($item)) :
                collect(),
            challengeRating: $model->challenge_rating,
            conditionImmune: $model->relationLoaded('conditionImmunities') ?
                $model->conditionImmunities->map(
                    fn (ConditionEdition $item) => $item->statusCondition->slug
                ) :
                [],
            gameEdition: $model->game_edition->toStringShort(),
            hitDieFaces: $model->hit_die_faces,
            hitPoints: $model->relationLoaded('hitPoints') && !empty($model->hitPoints) ?
                CreatureHitPointsDTO::fromModel($model->hitPoints) :
                null,
            immune: $model->damage_immunities->toArray(),
            isPlayable: $model->is_playable,
            lairXp: $model->lair_xp,
            movementSpeeds: $model->relationLoaded('movementSpeeds') ?
                $model->movementSpeeds->map(fn (MovementSpeed $item) => MovementSpeedDTO::fromModel($item)) :
                collect(),
            resist: $model->damage_resistances->toArray(),
            sizes: $model->sizes->map(fn (CreatureSizeUnit $item) => $item->toString())->toArray(),
            type: $model->relationLoaded('type') && !empty($model->type) ?
                CreatureTypeDTO::fromModel($model->type) :
                null
        );
    }
}
