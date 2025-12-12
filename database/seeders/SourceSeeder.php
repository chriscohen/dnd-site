<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Sources\Source;
use Illuminate\Support\Facades\Storage;

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

        $json = json_decode(Storage::disk('data')->get('/5etools/data/books.json'), true);

        foreach ($json['book'] as $item) {
            print "Creating 5eTools Source " . $item['name'] . "...\n";
            Source::fromFeJson($item);
        }
    }
}
