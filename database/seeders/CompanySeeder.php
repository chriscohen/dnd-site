<?php

namespace Database\Seeders;

use App\Models\Company;

class CompanySeeder extends AbstractYmlSeeder
{
    protected string $path = 'companies.json';
    protected string $model = Company::class;
    protected array $schema = [
        'id',
        'slug',
        'name',
        'short_name',
        'website',
    ];
}
