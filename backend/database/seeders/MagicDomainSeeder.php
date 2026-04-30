<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Magic\MagicDomain;

class MagicDomainSeeder extends AbstractYmlSeeder
{
    protected string $path = 'magic-domains.json';
    protected string $model = MagicDomain::class;
    protected array $schema = [
        'id',
        'name',
    ];
}
