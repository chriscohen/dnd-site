<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;

class FeatSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'feats';
    protected string $model = Feat::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            print "Creating Feat " . $datum['name'] . "...\n";
            Feat::fromInternalJson($datum);
        }
    }
}
