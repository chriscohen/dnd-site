<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Attribute;
use App\Enums\GameEdition;
use App\Models\Skills\Skill;
use App\Models\Skills\SkillEdition;

class SkillSeeder extends AbstractYmlSeeder
{
    protected string $path = 'skills.json';
    protected string $model = Skill::class;
    protected array $schema = [
        'id',
        'slug',
        'name',
        'related_attribute',
    ];
    protected array $excludedProperties = [
        'related_attribute',
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        // For each item in the JSON file...
        foreach ($data as $datum) {
            $skill = new Skill();
            $skill->id = $datum['id'];
            $skill->slug = $datum['slug'];
            $skill->name = $datum['name'];

            $skill->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new SkillEdition();
                $edition->skill()->associate($skill);

                $edition->alternate_name = $editionData['alternate_name'] ?? null;
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);

                if (!empty($editionData['related_attribute'])) {
                    $edition->related_attribute = Attribute::tryFromString($editionData['related_attribute']);
                }

                $edition->save();
            }
        }
    }
}
