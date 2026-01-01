<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Creatures\CreatureMainType;

class CreatureMainTypeSeeder extends AbstractYmlSeeder
{
    protected string $path = 'creature--main-types.json';
    protected string $model = Category::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            print "Creating CreatureType Type " . $datum['name'] . "...\n";
            CreatureMainType::fromInternalJson($datum);
        }
    }
}
