<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Species\Species;

class SpeciesSeeder extends AbstractYmlSeeder
{
    protected string $model = Species::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        $json = $this->getDataFromFile('5etools/data/races.json');

        foreach ($json['race'] as $datum) {
            print "[5e.tools] Creating Species " . $datum['name'] . "...\n";
            Species::from5eJson($datum);
        }
    }
}
