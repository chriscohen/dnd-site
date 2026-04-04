<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CharacterClasses\CharacterClass;

class CharacterClassSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'character-classes';
    protected string $model = CharacterClass::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            CharacterClass::fromInternalJson($datum);
        }
    }
}
