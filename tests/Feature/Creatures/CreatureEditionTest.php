<?php

declare(strict_types=1);

namespace Creatures;

use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;
use Tests\TestCase;

final class CreatureEditionTest extends TestCase
{
    public function testExample(): void
    {
        $creature = Creature::generate();

        $edition = CreatureEdition::generate($creature);
        $this->assertTrue(true);
    }

    public static function provider(): array
    {
        return [

        ];
    }

    public static function data01(): array
    {
        return [
            'name' => 'Factol Skall',
            'source' => 'AATM',
        ];
    }
}
