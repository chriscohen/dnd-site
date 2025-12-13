<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Species\Species;

class SpeciesSeeder extends AbstractYmlSeeder
{
    protected string $path = 'species.json';
    protected string $model = Species::class;
    protected array $schema = [
        'id',
        'slug',
        'name',
    ];
}
