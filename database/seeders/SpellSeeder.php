<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AreaType;
use App\Enums\Attribute;
use App\Enums\DamageType;
use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Enums\PerLevelMode;
use App\Enums\SavingThrows\SavingThrowMultiplier;
use App\Enums\SavingThrows\SavingThrowType;
use App\Enums\Spells\MaterialComponentMode;
use App\Enums\Spells\SpellFrequency;
use App\Enums\Spells\SpellType4e;
use App\Enums\TargetType;
use App\Enums\TimeUnit;
use App\Models\Area;
use App\Models\CharacterClass;
use App\Models\DamageInstance;
use App\Models\Duration;
use App\Models\Feats\Feat;
use App\Models\Items\Item;
use App\Models\Magic\MagicDomain;
use App\Models\Magic\MagicSchool;
use App\Models\Media;
use App\Models\Range;
use App\Models\SavingThrow;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEdition4e;
use App\Models\Spells\SpellEditionLevel;
use App\Models\Spells\SpellMaterialComponent;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use App\Models\Target;

class SpellSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'spells';
    protected string $model = Spell::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            $item = new Spell();
            $item->id = $datum['id'];
            $item->slug = $datum['slug'];
            $item->name = $datum['name'];

            print $item->name . PHP_EOL;

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
                    MaterialComponentMode::tryFromString($editionData['material_component_mode']) : null;

                // Range
                if (!empty($editionData['range'])) {
                    $this->makeRange($editionData['range'], $edition);
                }

                // Area
                if (!empty($editionData['area'])) {
                    $this->makeArea($editionData['area'], $edition);
                }

                // Domains
                foreach ($editionData['domains'] ?? [] as $domainData) {
                    $edition->domains->add(MagicDomain::query()->where('id', $domainData)->first());
                }

                $edition->spell_components = $editionData['spell_components'] ?? null;
                $edition->has_spell_resistance = $editionData['has_spell_resistance'] ?? null;

                if (!empty($editionData['school'])) {
                    $school = MagicSchool::query()->where('name', ucfirst($editionData['school']))->firstOrFail();
                    $edition->school()->associate($school);
                }

                // Casting time.
                $edition->casting_time_number = $editionData['casting_time_number'] ?? 1;
                $edition->casting_time_unit = TimeUnit::tryFromString($editionData['casting_time_unit']);

                $edition->save();

                // Saving throws
                if (!empty($editionData['saving_throw'])) {
                    $this->makeSavingThrow($editionData['saving_throw'], $edition);
                }

                // duration.
                $this->makeDuration($editionData['duration'], $edition);

                // 4th edition stuff.
                if ($edition->game_edition === GameEdition::FOURTH) {
                    $this->make4e($editionData, $edition);
                }

                // Damage.
                $this->makeDamageInstances($editionData['damage'] ?? [], $edition, $editionData);

                // Levels.
                foreach ($editionData['levels'] as $levelData) {
                    $this->makeLevel($levelData, $edition);
                }

                // Material components
                foreach ($editionData['material_components'] ?? [] as $materialData) {
                    $this->makeMaterialComponent($materialData, $edition);
                }

                // Target
                if (!empty($editionData['target'])) {
                    $this->makeTarget($editionData['target'], $edition);
                }

                // References
                if (!empty($editionData['references'])) {
                    $this->setReferences($editionData['references'], $edition);
                }
            }
        }
    }

    protected function make4e(array $data, SpellEdition $edition): void
    {
        $spellEdition4e = new SpellEdition4e();
        $spellEdition4e->spellEdition()->associate($edition);

        $spellEdition4e->type = SpellType4e::tryFromString($data['spell_type']);
        $spellEdition4e->frequency = SpellFrequency::tryFromString($data['spell_frequency']);
        //$spellEdition4e->

        $spellEdition4e->save();
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

    protected function makeDamageInstances(array $data, SpellEdition $edition, array $editionData): void
    {
        foreach ($data as $datum) {
            $damageInstance = new DamageInstance();
            $damageInstance->entity()->associate($edition);

            $damageInstance->die_quantity = $datum['die_quantity'] ?? null;
            $damageInstance->die_quantity_maximum = $datum['die_quantity_maximum'] ?? null;
            $damageInstance->die_faces = $datum['die_faces'] ?? null;

            if (!empty($datum['damage_type'])) {
                $damageInstance->damage_type = DamageType::tryFromString($datum['damage_type']);
            }

            $damageInstance->modifier = $datum['modifier'] ?? 0;

            if (!empty($datum['attribute_modifier'])) {
                $damageInstance->attribute_modifier = Attribute::tryFromString($datum['attribute_modifier']);
                $damageInstance->attribute_modifier_quantity = $datum['attribute_modifier_quantity'] ?? 1;
            }

            $damageInstance->per_level_mode = empty($datum['per_level_mode']) ?
                PerLevelMode::NONE :
                PerLevelMode::tryFromString($datum['per_level_mode']);

            if (!empty($datum['status_condition'])) {
                $statusConditionEdition = StatusConditionEdition::query()
                    ->where('game_edition', GameEdition::tryFromString($editionData['game_edition'])->value)
                    ->whereHas('statusCondition', function ($query) use ($datum) {
                    })
                    ->firstOrFail();
                $damageInstance->statusConditionEdition()->associate($statusConditionEdition);
            }

            $damageInstance->save();
        }
    }

    protected function makeDuration(array $data, SpellEdition $edition): void
    {
        $duration = new Duration();
        $duration->entity()->associate($edition);

        $duration->per_level = $data['per_level'] ?? null;
        $duration->per_level_mode = empty($data['per_level_mode']) ?
            null :
            PerLevelMode::tryFromString($data['per_level_mode']);
        $duration->unit = TimeUnit::tryFromString($data['unit']);
        $duration->value = $data['value'] ?? null;

        $duration->save();
    }

    protected function makeLevel(array $data, SpellEdition $edition): void
    {
        if (!empty($data['class'])) {
            $entity = CharacterClass::query()->where('id', $data['class'])->firstOrFail();
        } else {
            $feat = Feat::query()->where('id', $data['feat'])->firstOrFail();
            $entity = $feat->editions()->firstOrFail();
        }

        $level = new SpellEditionLevel();
        $level->entity()->associate($entity);
        $level->spellEdition()->associate($edition);
        $level->level = $data['level'];

        $level->save();
    }

    protected function makeMaterialComponent(array $data, SpellEdition $edition): void
    {
        $material = new SpellMaterialComponent();
        $material->spellEdition()->associate($edition);

        $materialItem = Item::query()->where('slug', $data['item'])->firstOrFail();
        $itemEdition = $materialItem->defaultEdition();
        $material->itemEdition()->associate($itemEdition);

        $material->description = $data['description'] ?? null;
        $material->quantity = $data['quantity'] ?? 1;
        $material->quantity_text = $data['quantity_text'] ?? null;
        $material->is_consumed = $data['is_consumed'] ?? false;
        $material->is_plural = $data['is_plural'] ?? false;

        $material->save();
    }

    protected function makeRange(array $data, SpellEdition $edition): void
    {
        $range = new Range();
        $range->number = $data['number'] ?? null;
        $range->per_level = $data['per_level'] ?? null;
        $range->unit = !empty($data['unit']) ?
            Distance::tryFromString($data['unit']) :
            null;
        $range->is_self = $data['is_self'] ?? false;
        $range->is_touch = $data['is_touch'] ?? false;
        $range->save();
        $edition->range()->associate($range);
    }

    protected function makeSavingThrow(array $data, SpellEdition $edition): void
    {
        $savingThrow = new SavingThrow();
        $savingThrow->spellEdition()->associate($edition);
        $savingThrow->type = SavingThrowType::tryFromString($data['type']);

        if (!empty($data['multiplier'])) {
            $savingThrow->multiplier = SavingThrowMultiplier::tryFromString($data['multiplier']);
        }

        if (!empty($data['fail_status'])) {
            $condition = StatusCondition::query()
                ->where('slug', $data['fail_status'])
                ->first();

            if (empty($condition)) {
                throw new \Exception("Invalid fail_status: " . $data['fail_status']);
            }

            $savingThrow->failStatus()->associate(
                $condition->editions->where('game_edition', $edition->game_edition)->first()
            );
        }

        $savingThrow->save();
    }

    protected function makeTarget(array $data, SpellEdition $edition): void
    {
        $target = new Target();
        $target->spellEdition()->associate($edition);
        $target->type = TargetType::tryFromString($data['type']);

        $target->description = $data['description'] ?? null;
        $target->in_area = $data['in_area'] ?? false;
        $target->quantity = $data['quantity'] ?? 1;
        $target->per_level = $data['per_level'] ?? null;
        $target->per_level_mode = empty($data['per_level_mode']) ?
            null :
            PerLevelMode::tryFromString($data['per_level_mode']);
        $target->save();
    }
}
