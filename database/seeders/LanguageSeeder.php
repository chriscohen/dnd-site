<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Exceptions\RecordNotFoundException;
use App\Models\Languages\Language;

class LanguageSeeder extends AbstractYmlSeeder
{
    protected string $path = 'languages.json';
    protected string $model = Language::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile('5etools/data/languages.json');

        // For each item in the JSON file...
        foreach ($data['language'] as $datum) {
            print "[5e.tools] Creating Language " . $datum['name'] . "...\n";

            try {
                Language::fromInternalJson($datum);
            } catch (RecordNotFoundException $e) {
                print $e->getMessage() . "\n";
            }
        }
    }
}
