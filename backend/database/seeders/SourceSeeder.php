<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Exceptions\DuplicateRecordException;
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
        // Seed from internal (non-5e.tools) data.
        foreach ($this->getDataFromDirectory() as $datum) {
            print "[Internal] Creating Source " . $datum['name'] . "...\n";
            Source::fromInternalJson($datum);
        }

        // Seed from 5e.tools "books.json" single file.
        $json = $this->getDataFromFile('5etools/data/books.json');

        foreach ($json['book'] as $item) {
            print "[5e.tools] Creating Source " . $item['name'] . "...\n";
            Source::from5eJson($item);
        }

        // Seed from 5e.tools "adventures.json" single file.
        $json = $this->getDataFromFile('5etools/data/adventures.json');

        foreach ($json['adventure'] as $item) {
            print "[5e.tools] Creating Adventure Source " . $item['name'] . "...\n";

            // Inject a marker so we can tell it's an adventure.
            $item['isAdventure'] = true;

            try {
                Source::from5eJson($item);
            } catch (DuplicateRecordException $e) {
                print "[5e.tools] Skipping duplicate: " . $item['name'] . "\n";
            }
        }

        // Extra data not found in 5e.tools JSON.
        $extra = json_decode(Storage::disk('data')->get('/5etools-x/data/books.json'), true);

        foreach ($extra['book'] as $item) {
            print "[Extra] Adding extra data for 5e.tools Source " . $item['name'] . "...\n";
            Source::fromFeJsonExtra($item);
        }
    }
}
