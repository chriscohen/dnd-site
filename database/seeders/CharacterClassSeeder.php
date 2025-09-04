<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CharacterClass;

class CharacterClassSeeder extends AbstractYmlSeeder
{
    protected string $path = 'character-classes.json';
    protected string $model = CharacterClass::class;

    protected array $schema = [
        'id',
        'name',
    ];
}
