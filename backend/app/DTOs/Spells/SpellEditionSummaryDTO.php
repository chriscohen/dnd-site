<?php

declare(strict_types=1);

namespace App\DTOs\Spells;

use App\DTOs\AbstractDTO;
use App\DTOs\Magic\MagicSchoolFullDTO;
use App\Models\ModelInterface;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEditionLevel;
use Illuminate\Support\Collection;

readonly class SpellEditionSummaryDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $gameEdition,
        public ?bool $hasSpellResistance = null,
        public ?bool $isDefault = null,
        /** @var Collection<SpellEditionLevelDTO> $levels */
        public Collection $levels,
        public ?MagicSchoolFullDTO $school = null
    ) {
    }

    /**
     * @param SpellEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            gameEdition: $model->game_edition,
            hasSpellResistance: $model->has_spell_resistance,
            isDefault: $model->is_default,
            levels: $model->levels->map(fn (SpellEditionLevel $item) => SpellEditionLevelDTO::fromModel($item)),
            school: !empty($model->school) ? MagicSchoolFullDTO::fromModel($model->school) : null,
        );
    }
}
