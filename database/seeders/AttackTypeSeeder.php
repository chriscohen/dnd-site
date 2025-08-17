<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AttackType;

class AttackTypeSeeder extends AbstractYmlSeeder
{
    protected string $path = 'attack-types.json';
    protected string $model = AttackType::class;
    protected array $schema = [
        'id',
        'name',
    ];
}
