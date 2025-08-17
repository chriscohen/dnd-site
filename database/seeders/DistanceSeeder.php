<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Distance;

class DistanceSeeder extends AbstractYmlSeeder
{
    protected string $path = 'distances.json';
    protected string $model = Distance::class;
    protected array $schema = [
        'id',
        'short_name',
        'plural',
        'per_meter',
    ];
}
