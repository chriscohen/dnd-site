<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AbilityScoreType;
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
            print "Creating Skill " . $datum['name'] . "...\n";
            Skill::fromInternalJson($datum);
        }
    }
}
