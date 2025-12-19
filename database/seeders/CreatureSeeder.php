<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;

class CreatureSeeder extends AbstractYmlSeeder
{
    protected string $model = Creature::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        $json = $this->getDataFromFile('5etools/data/races.json');

        foreach ($json['race'] as $datum) {
            print "[5e.tools] Creating Creature (Race) " . $datum['name'] . "...\n";
            $creature = Creature::from5eJson($datum);
            // We need to load from the db to get the edition data.
            $creature = Creature::query()->find($creature->id);

            // This is a "race" in 5e.tools so set the is_playable flag to true.
            /** @var CreatureEdition $edition */
            $edition = $creature->editions->first();
            $edition->is_playable = true;
            $edition->save();
        }
    }
}
