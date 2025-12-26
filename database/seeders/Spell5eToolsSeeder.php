<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Spells\Spell;
use Illuminate\Support\Facades\Storage;

class Spell5eToolsSeeder extends AbstractYmlSeeder
{
    protected string $dir = '5etools/data/spells';
    protected string $model = Spell::class;

    public function run(): void
    {
        foreach (Storage::disk('data')->files($this->dir) as $file) {
            if (!str_contains($file, 'spells-') || str_contains($file, 'fluff')) {
                continue;
            }

            $json = json_decode(Storage::disk('data')->get($file), true);

            foreach ($json['spell'] as $datum) {
                print "[5e.tools] Creating Spell " . $datum['name'] . "...\n";
                Spell::from5eJson($datum);
            }
        };
    }
}
