<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Magic\MagicSchool;

class MagicSchoolSeeder extends AbstractYmlSeeder
{
    protected string $path = 'magic-schools.json';
    protected string $model = MagicSchool::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            MagicSchool::fromInternalJson($datum);
        }
    }
}
