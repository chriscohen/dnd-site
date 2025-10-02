<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CharacterClasses\CharacterClass;

class CharacterClassController extends AbstractController
{
    protected string $entityType = CharacterClass::class;
}
