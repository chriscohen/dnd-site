<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Feats\Feature;

class FeatureSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'features';
    protected string $model = Feature::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            print "[Internal] Creating Feature " . $datum['name'] . "...\n";
            Feature::fromInternalJson($datum);
        }
    }
}
