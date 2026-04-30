<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Deity;

class DeitySeeder extends AbstractYmlSeeder
{
    protected string $path = 'deities.json';
    protected string $model = Deity::class;
    protected array $schema = [
        'id',
    ];
}
