<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Attribute;
use App\Models\Skill;

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
            $skill->related_attribute = Attribute::tryFromString($datum['related_attribute']);
            $skill->save();
        }
    }
}
