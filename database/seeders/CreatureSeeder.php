<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;
use Illuminate\Support\Facades\Storage;

class CreatureSeeder extends AbstractYmlSeeder
{
    protected string $model = Creature::class;
    protected array $dependsOn = [
        SourceSeeder::class,
    ];

    public function run(): void
    {
        /**
         * Import 5e.tools races from JSON.
         */
        $json = $this->getDataFromFile('5etools/data/races.json');

        foreach ($json['race'] as $datum) {
            print "[5e.tools] Creating Creature (Race) " . $datum['name'] . "...\n";
            $creature = Creature::from5eJson($datum);
            // We need to load from the db to get the edition data.
            $creature = Creature::query()->find($creature->id);

            // This is a "race" in 5e.tools so set the is_playable flag to true.
            /** @var CreatureEdition $edition */
            $edition = $creature->editions->first();
            $edition->is_playable = true;
            $edition->save();
        }

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

                print "[5e.tools] Creating Creature (" . $filename . ") " . $datum['name'] . "...\n";
                $creatureType = Creature::from5eJson($datum);
                /** @var CreatureEdition $edition */
                $edition = $creatureType->editions->firstOrFail();

                // Look for extra data.
                $extraPath = '/5etools-x/data/creature-types/' . $creatureType->slug . '.json';
                if (Storage::disk('data')->exists($extraPath)) {
                    print "[Extra] Adding extra data for 5e.tools Creature " . $creatureType->name . "...\n";
                    $json = json_decode(Storage::disk('data')->get($extraPath), true);

                    $edition->fromExtraData($json, $creatureType);
                }
            }
        }
    }
}
