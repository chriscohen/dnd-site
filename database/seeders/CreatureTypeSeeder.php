<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\CreatureType;
use App\Models\Creatures\CreatureTypeEdition;
use Illuminate\Support\Facades\Storage;

class CreatureTypeSeeder extends AbstractYmlSeeder
{
    protected string $model = CreatureType::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        /**
         * Import from 5e.tools bestiary files.
         */
        foreach (Storage::disk('data')->files('/5etools/data/bestiary') as $file) {
            if (str_contains('fluff', $file)) {
                print "[5e.tools] Skipping fluff file: " . $file . "\n";
                continue;
            }

            $json = json_decode(Storage::disk('data')->get($file), true);
            $pieces = explode('/', $file);
            $filename = end($pieces);

            /**
             * Import each monster in the file.
             */
            foreach ($json['monster'] as $datum) {
                if (!empty($datum['_copy'])) {
                    print "[5e.tools] Skipping copy: " . $datum['name'] . "\n";
                    continue;
                }

                $creatureType = CreatureType::query()->where('name', $datum['name'])->first();

                if (empty($creatureType)) {
                    print "[5e.tools] Creating CreatureType (" . $filename . ") " . $datum['name'] . "...\n";
                    $creatureType = CreatureType::from5eJson($datum);
                } else {
                    print "[5e.tools] Using existing CreatureType for " . $datum['name'] . "...\n";
                }

                /** @var CreatureTypeEdition $edition */
                $edition = $creatureType->editions->firstOrFail();

                // Look for extra data.
                $extraPath = '/5etools-x/data/creature-types/' . $creatureType->slug . '.json';
                if (Storage::disk('data')->exists($extraPath)) {
                    print "[Extra] Adding extra data for 5e.tools CreatureType " . $creatureType->name . "...\n";
                    $json = json_decode(Storage::disk('data')->get($extraPath), true);

                    $edition->fromExtraData($json, $creatureType);
                }
            }
        }
    }
}
