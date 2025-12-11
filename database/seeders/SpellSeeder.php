<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Spells\Spell;

class SpellSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'spells';
    protected string $model = Spell::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            Spell::fromInternalJson($datum);
        }
    }

}
