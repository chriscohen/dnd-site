<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Feats\Feature;
use App\Models\Feats\FeatureEdition;

class FeatSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'feats';
    protected string $model = Feature::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            print "Creating Feature " . $datum['name'] . "...\n";
            Feature::fromInternalJson($datum);
        }
    }
}
