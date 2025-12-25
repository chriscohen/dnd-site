<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Spells\Spell;
use Ramsey\Uuid\Uuid;

class Spell5eToolsSeeder extends AbstractYmlSeeder
{
    protected string $dir = '5etools/data/spells';
    protected string $model = Spell::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            $item = new Spell();
            $item->id = Uuid::uuid4();
            $item->name = $datum['name'];
            $item->slug = $this->makeSlug($datum['name']);
        }
    }
}
