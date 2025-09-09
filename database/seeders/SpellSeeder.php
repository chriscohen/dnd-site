<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Enums\MaterialComponentMode;
use App\Models\CharacterClass;
use App\Enums\Distance;
use App\Models\Items\Item;
use App\Models\Magic\MagicSchool;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEditionCharacterClassLevel;
use App\Models\Spells\SpellMaterialComponent;

class SpellSeeder extends AbstractYmlSeeder
{
    protected string $path = 'spells.json';
    protected string $model = Spell::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $spell = new Spell();
            $spell->id = $datum['id'];
            $spell->slug = $datum['slug'];
            $spell->name = $datum['name'];

            $spell->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new SpellEdition();
                $edition->spell()->associate($spell);

                $edition->description = $editionData['description'] ?? null;
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->higher_level = $editionData['higher_level'] ?? null;
                $edition->is_default = $editionData['is_default'] ?? false;
                $edition->material_component_mode = !empty($editionData['material_component_mode']) ?
                    MaterialComponentMode::tryFrom($editionData['material_component_mode']) : null;
                $edition->range_number = $editionData['range_number'] ?? null;
                $edition->range_unit = !empty($editionData['range_unit']) ?
                    Distance::tryFromString($editionData['range_unit']) :
                    null;
                $edition->range_is_self = $editionData['range_is_self'] ?? false;
                $edition->range_is_touch = $editionData['range_is_touch'] ?? false;

                $school = MagicSchool::query()->where('id', $editionData['school'])->firstOrFail();
                $edition->school()->associate($school);

                $edition->save();

                foreach ($editionData['classes'] as $classData) {
                    $class = CharacterClass::query()->where('id', $classData['class'])->firstOrFail();

                    $sccl = new SpellEditionCharacterClassLevel();
                    $sccl->characterClass()->associate($class);
                    $sccl->spellEdition()->associate($edition);
                    $sccl->level = $classData['level'];

                    $sccl->save();
                }

                foreach ($editionData['material_components'] ?? [] as $materialData) {
                    $material = new SpellMaterialComponent();
                    $material->spellEdition()->associate($edition);

                    $item = Item::query()->where('slug', $materialData['item'])->firstOrFail();
                    $itemEdition = $item->primaryEdition();
                    $material->itemEdition()->associate($itemEdition);

                    $material->quantity = $materialData['quantity'] ?? 1;
                    $material->is_consumed = $materialData['is_consumed'] ?? false;

                    $material->save();
                }

                if (!empty($editionData['references'])) {
                    $this->setReferences($editionData['references']);
                }
            }
        }
    }
}
