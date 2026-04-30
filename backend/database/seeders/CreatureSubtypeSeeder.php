<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\CreatureSubtype;

class CreatureSubtypeSeeder extends AbstractYmlSeeder
{
    protected string $path = 'creature-subtypes.json';
    protected string $model = CreatureSubtype::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            print "[Internal] Creating CreatureType Subtype " . $datum['name'] . "...\n";
            CreatureSubtype::fromInternalJson($datum);
        }
    }
}
