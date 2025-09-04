<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Spells\SpellComponentType;

class SpellComponentSeeder extends AbstractYmlSeeder
{
    protected string $path = 'spell-component-types.json';
    protected string $model = SpellComponentType::class;
    protected array $schema = [
        'id',
        'name',
    ];
}
