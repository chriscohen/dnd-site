<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Sources\Source;

class SourceSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'sources';
    protected string $model = Source::class;
    protected array $excludedProperties = [
        'cover_image'
    ];

    protected array $dependsOn = [
        CampaignSettingSeeder::class,
    ];

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            print "Creating Source " . $datum['name'] . "...\n";
            Source::fromInternalJson($datum);
        }
    }
}
