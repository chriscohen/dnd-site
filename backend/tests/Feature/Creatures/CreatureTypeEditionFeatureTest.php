<?php

declare(strict_types=1);

namespace Creatures;

use App\Models\Creatures\CreatureType;
use App\Models\Creatures\CreatureTypeEdition;
use Tests\FeatureTestCase;

final class CreatureTypeEditionFeatureTest extends FeatureTestCase
{
    public function testGenerate(): void
    {
        // Generate a creature edition.
        $creature = CreatureType::generate();
        $edition = CreatureTypeEdition::generate($creature);

    }

    public static function provider(): array
    {
        return [

        ];
    }
}
