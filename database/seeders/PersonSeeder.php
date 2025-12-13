<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Support\Facades\Storage;

class PersonSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'people';
    protected string $model = Person::class;

    public function run(): void
    {
        $json = json_decode(Storage::disk('data')->get($this->dir . DIRECTORY_SEPARATOR . 'people.json'), true);

        foreach ($json as $datum) {
            print "[Internal] Creating Person " . $datum['firstName'] . ' ' . $datum['lastName'] . "...\n";
            Person::fromInternalJson($datum);
        }
    }
}
