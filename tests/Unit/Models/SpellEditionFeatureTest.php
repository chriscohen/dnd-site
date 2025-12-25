<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Sources\SourceEdition;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use Tests\FeatureTestCase;

class SpellEditionFeatureTest extends FeatureTestCase
{
    public function getTestData(): array
    {
        return [
            'name' => 'Air Bubble',
            'source' => 'AAG',
            'page' => 22,
            'level' => 2,
            'school' => 'C',
            'time' => [
                [
                    'number' => 1,
                    'unit' => 'action',
                ]
            ],
            'range' => [
                'type' => 'point',
                'distance' => [
                    'type' => 'feet',
                    'amount' => 40,
                ]
            ],
            'components' => [
                's' => true,
            ],
            'duration' => [
                [
                    'type' => 'timed',
                    'duration' => [
                        'type' => 'hour',
                        'amount' => 24
                    ]
                ]
            ],
            'entries' => [
                "You create a spectral globe around the head of a willing creature you can see within range. The globe is filled with fresh air that lasts until the spell ends. If the creature has more than one head, the globe of air appears around only one of its heads (which is all the creature needs to avoid suffocation, assuming that all its heads share the same respiratory system).",
            ],
            'entriesHigherLevel' => [
                [
                    'type' => 'entries',
                    'name' => 'At Higher Levels',
                    'entries' => [
                        "When you cast this spell using a spell slot of 3rd level or higher, you can create two additional globes of fresh air for each slot level above 2nd."
                    ]
                ]
            ],
            'miscTags' => [
                'SGT'
            ],
            'hasFluffImages' => true,
        ];
    }

    public function testFromFeJson(): void
    {
        $edition = Spell::from5eJson($this->getTestData());
        $this->assertEquals('Air Bubble', $edition->name);
    }
}
