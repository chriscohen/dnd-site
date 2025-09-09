<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\CharacterClass;
use App\Enums\Distance;
use App\Models\Magic\MagicSchool;
use App\Models\Reference;
use App\Models\Source;
use App\Models\SourceEdition;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEditionCharacterClassLevel;

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

                foreach ($editionData['references'] as $referenceData) {
                    $reference = new Reference();

                    // If there's a specific sourcebook edition ID, use that. If not, use the first edition of the
                    // sourcebook.
                    if (!empty($referenceData['edition_id'])) {
                        $sourceEdition = SourceEdition::query()
                            ->where('id', $referenceData['edition_id'])
                            ->firstOrFail();
                    } else {
                        $source = Source::query()->where('slug', $referenceData['source'])->firstOrFail();
                        $sourceEdition = $source->editions()->first();
                    }

                    $reference->edition()->associate($sourceEdition);
                    $reference->page_from = $referenceData['page_from'];
                    $reference->page_to = $referenceData['page_to'] ?? null;
                    $reference->entity()->associate($edition);

                    $reference->save();
                }
            }
        }
    }
}
