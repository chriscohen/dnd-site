<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Enums\MaterialComponentMode;
use App\Enums\SpellComponentType;
use App\Models\CharacterClass;
use App\Enums\Distance;
use App\Models\Items\Item;
use App\Models\Magic\MagicSchool;
use App\Models\Range;
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
                $edition->focus = $editionData['focus'] ?? null;
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->higher_level = $editionData['higher_level'] ?? null;
                $edition->is_default = $editionData['is_default'] ?? false;
                $edition->material_component_mode = !empty($editionData['material_component_mode']) ?
                    MaterialComponentMode::tryFrom($editionData['material_component_mode']) : null;

                // Range
                $range = new Range();
                $range->number = $editionData['range']['number'] ?? null;
                $range->per_level = $editionData['range']['per_level'] ?? null;
                $range->unit = !empty($editionData['range']['unit']) ?
                    Distance::tryFromString($editionData['range']['unit']) :
                    null;
                $range->is_self = $editionData['range']['is_self'] ?? false;
                $range->is_touch = $editionData['range']['is_touch'] ?? false;
                $range->save();
                $edition->range()->associate($range);
                $edition->spell_components = $editionData['spell_components'];

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
