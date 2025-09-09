<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Magic\MagicSchool;

class MagicSchoolSeeder extends AbstractYmlSeeder
{
    protected string $path = 'magic-schools.json';
    protected string $model = MagicSchool::class;
    protected array $schema = [
        'id',
        'name',
    ];
}
