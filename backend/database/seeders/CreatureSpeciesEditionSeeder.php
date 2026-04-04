<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\CreatureSpeciesEdition;
use App\Models\Creatures\CreatureType;

class CreatureSpeciesEditionSeeder extends AbstractYmlSeeder
{
    protected string $model = CreatureType::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        /**
         * Import 5e.tools races from JSON.
         */
        $json = $this->getDataFromFile('5etools/data/races.json');

        foreach ($json['race'] as $datum) {
            $creatureType = CreatureType::query()->where('name', $datum['name'])->first();

            if (empty($creatureType)) {
                print "[5e.tools] Creating CreatureType (Species) " . $datum['name'] . "...\n";
                $creatureType = CreatureType::from5eJson($datum);
            } else {
                print "[5e.tools] Using existing CreatureType for species " . $datum['name'] . "...\n";
            }

            CreatureSpeciesEdition::from5eJson($datum, $creatureType);
        }
    }
}
