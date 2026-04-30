<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CampaignSetting;

class CampaignSettingSeeder extends AbstractYmlSeeder
{
    protected string $path = 'campaign-settings.json';
    protected string $model = CampaignSetting::class;

    protected array $dependsOn = [
        CompanySeeder::class,
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            print "Creating Campaign Setting " . $datum['name'] . "...\n";
            CampaignSetting::fromInternalJson($datum);
        }
    }
}
