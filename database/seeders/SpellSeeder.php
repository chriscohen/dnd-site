<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AreaType;
use App\Enums\DamageType;
use App\Enums\GameEdition;
use App\Enums\MaterialComponentMode;
use App\Enums\PerLevelMode;
use App\Enums\SavingThrowMultiplier;
use App\Enums\SavingThrowType;
use App\Enums\SpellComponentType;
use App\Enums\TimeUnit;
use App\Models\Area;
use App\Models\CharacterClass;
use App\Enums\Distance;
use App\Models\DamageInstance;
use App\Models\Items\Item;
use App\Models\Magic\MagicSchool;
use App\Models\Media;
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
            $item = new Spell();
            $item->id = $datum['id'];
            $item->slug = $datum['slug'];
            $item->name = $datum['name'];

            $media = Media::createFromExisting([
                'filename' => '/spells/' . $datum['image'],
                'disk' => 's3',
                'collection_name' => 'spells',
            ]);
            $item->image()->associate($media);

            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new SpellEdition();
                $edition->spell()->associate($item);

                $edition->description = $editionData['description'] ?? null;
                $edition->focus = $editionData['focus'] ?? null;
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->higher_level = $editionData['higher_level'] ?? null;
                $edition->is_default = $editionData['is_default'] ?? false;
                $edition->material_component_mode = !empty($editionData['material_component_mode']) ?
                    MaterialComponentMode::tryFrom($editionData['material_component_mode']) : null;

                // Range
                $this->makeRange($editionData['range'], $edition);

                // Area
                if (!empty($editionData['area'])) {
                    $this->makeArea($editionData['area'], $edition);
                }

                // Saving throws
                $edition->has_saving_throw = $editionData['has_saving_throw'] ?? null;
                $edition->saving_throw_multiplier = !empty($editionData['saving_throw_multiplier']) ?
                    SavingThrowMultiplier::tryFromString($editionData['saving_throw_multiplier']) : null;
                $edition->saving_throw_type = !empty($editionData['saving_throw_type']) ?
                    SavingThrowType::tryFromString($editionData['saving_throw_type']) : null;

                $edition->spell_components = $editionData['spell_components'] ?? null;
                $edition->has_spell_resistance = $editionData['has_spell_resistance'] ?? null;

                $school = MagicSchool::query()->where('name', ucfirst($editionData['school']))->firstOrFail();
                $edition->school()->associate($school);

                // Casting time.
                $edition->casting_time_number = $editionData['casting_time_number'] ?? 1;
                $edition->casting_time_unit = TimeUnit::tryFromString($editionData['casting_time_unit']);

                $edition->save();

                // Damage.
                $this->makeDamageInstances($editionData['damage'] ?? [], $edition);

                // Character classes.
                foreach ($editionData['classes'] as $classData) {
                    $this->makeCharacterClass($classData, $edition);
                }

                foreach ($editionData['material_components'] ?? [] as $materialData) {
                    $material = new SpellMaterialComponent();
                    $material->spellEdition()->associate($edition);

                    $materialItem = Item::query()->where('slug', $materialData['item'])->firstOrFail();
                    $itemEdition = $materialItem->primaryEdition();
                    $material->itemEdition()->associate($itemEdition);

                    $material->quantity = $materialData['quantity'] ?? 1;
                    $material->is_consumed = $materialData['is_consumed'] ?? false;

                    $material->save();
                }

                if (!empty($editionData['references'])) {
                    $this->setReferences($editionData['references'], $edition);
                }
            }
        }
    }

    protected function makeArea(array $data, SpellEdition $edition): void
    {
        $area = new Area();
        $area->type = AreaType::tryFromString($data['type']);
        $area->height = $data['height'] ?? null;
        $area->length = $data['length'] ?? null;
        $area->radius = $data['radius'] ?? null;
        $area->save();
        $edition->area()->associate($area);
    }

    protected function makeCharacterClass(array $data, SpellEdition $edition): void
    {
        $class = CharacterClass::query()->where('id', $data['class'])->firstOrFail();

        $sccl = new SpellEditionCharacterClassLevel();
        $sccl->characterClass()->associate($class);
        $sccl->spellEdition()->associate($edition);
        $sccl->level = $data['level'];

        $sccl->save();
    }

    protected function makeDamageInstances(array $data, SpellEdition $edition): void
    {
        foreach ($data as $datum) {
            $damageInstance = new DamageInstance();
            $damageInstance->entity()->associate($edition);

            $damageInstance->die_quantity = $datum['die_quantity'] ?? null;
            $damageInstance->die_quantity_maximum = $datum['die_quantity_maximum'] ?? null;
            $damageInstance->die_faces = $datum['die_faces'];
            $damageInstance->damage_type = DamageType::tryFromString($datum['damage_type']);
            $damageInstance->modifier = $datum['modifier'] ?? 0;
            $damageInstance->per_level_mode = PerLevelMode::tryFromString($datum['per_level_mode']);

            $damageInstance->save();
        }
    }

    protected function makeRange(array $data, SpellEdition $edition): void
    {
        $range = new Range();
        $range->number = $data['range']['number'] ?? null;
        $range->per_level = $data['range']['per_level'] ?? null;
        $range->unit = !empty($data['range']['unit']) ?
            Distance::tryFromString($data['range']['unit']) :
            null;
        $range->is_self = $data['range']['is_self'] ?? false;
        $range->is_touch = $data['range']['is_touch'] ?? false;
        $range->save();
        $edition->range()->associate($range);
    }
}
