<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationType;
use App\Models\CampaignSetting;
use App\Models\Company;

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
            $item = new CampaignSetting();
            $item->id = $datum['id'];
            // If no slug, assume we can just urlencode the name.
            $item->slug = $datum['slug'] ?? self::makeSlug($datum['name']);
            $item->name = $datum['name'];
            $item->short_name = $datum['short_name'];

            $publisher = Company::query()->where('slug', $datum['publisher'])->firstOrFail();
            $item->publisher()->associate($publisher);

            $item->publication_type = PublicationType::tryFromString($datum['publication_type']);
            $item->logo = $datum['logo'] ?? null;
            $item->save();
        }
    }
}
