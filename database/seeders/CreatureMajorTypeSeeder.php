<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Creatures\CreatureMajorType;

class CreatureMajorTypeSeeder extends AbstractYmlSeeder
{
    protected string $path = 'creature-major-types.json';
    protected string $model = Category::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            print "Creating Creature Major Type " . $datum['name'] . "...\n";
            CreatureMajorType::fromInternalJson($datum);
        }
    }
}
