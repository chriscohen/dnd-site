<?php

declare(strict_types=1);

namespace App\DTOs\Spells;

use App\DTOs\Magic\MagicSchoolFullDTO;
use App\Models\ModelInterface;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEditionLevel;
use Illuminate\Support\Collection;

readonly class SpellEditionFullDTO extends SpellEditionSummaryDTO
{
    public function __construct(
        string $id,
        string $gameEdition,
        ?bool $hasSpellResistance,
        ?bool $isDefault,
        ?int $lowestLevel,
        /** @var Collection<SpellEditionLevelDTO> $levels */
        Collection $levels,
        ?MagicSchoolFullDTO $school = null,
        // Summary.
        public SpellSummaryDTO $spell
    ) {
        parent::__construct($id, $gameEdition, $hasSpellResistance, $isDefault, $lowestLevel, $levels, $school);
    }

    /**
     * @param SpellEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            gameEdition: $model->game_edition->toStringShort(),
            hasSpellResistance: $model->has_spell_resistance,
            isDefault: $model->is_default,
            lowestLevel: $model->getLowestLevel(),
            levels: $model->levels->map(fn (SpellEditionLevel $item) => SpellEditionLevelDTO::fromModel($item)),
            school: !empty($model->school) ? MagicSchoolFullDTO::fromModel($model->school) : null,
            // Summary.
            spell: SpellSummaryDTO::fromModel($model->spell),
        );
    }
}
