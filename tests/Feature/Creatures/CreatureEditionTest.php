<?php

declare(strict_types=1);

namespace Creatures;

use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;
use Tests\TestCase;

final class CreatureEditionTest extends TestCase
{
    public function testGenerate(): void
    {
        // Generate a creature edition.
        $creature = Creature::generate();
        $edition = CreatureEdition::generate($creature);

    }

    public static function provider(): array
    {
        return [

        ];
    }
}
