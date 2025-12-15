<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\Creature;

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
            Creature::from5eJson($datum);
        }
    }
}
