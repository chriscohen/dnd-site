<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

use App\Http\Controllers\AbstractController;
use App\Models\Spells\Spell;

class SpellController extends AbstractController
{
    protected string $entityType = Spell::class;
    protected string $orderKey = 'name';
}
