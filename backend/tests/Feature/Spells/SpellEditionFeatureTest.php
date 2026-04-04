<?php

declare(strict_types=1);

namespace Tests\Feature\Spells;

use App\Enums\PublicationType;
use App\Enums\Sources\SourceType;
use App\Enums\Units\DistanceUnit;
use App\Enums\Units\TimeUnit;
use App\Models\Duration;
use App\Models\Magic\MagicSchool;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use Tests\FeatureTestCase;

final class SpellEditionFeatureTest extends FeatureTestCase
{
    public function testFrom5eJson(): void
    {
        $phb = new Source();
        $phb->name = "Player's Handbook 2014";
        $phb->slug = 'players-handbook-2014';
        $phb->shortName = 'PHB';
        $phb->source_type = SourceType::SOURCEBOOK;
        $phb->publication_type = PublicationType::OFFICIAL;
        $phb->save();
        $phbEdition = new SourceEdition();
        $phbEdition->name = 'original';
        $phbEdition->source()->associate($phb);
        $phbEdition->save();

        $magicSchool = new MagicSchool();
        $magicSchool->name = 'Necromancy';
        $magicSchool->id = 'necromancy';
        $magicSchool->shortName = 'N';
        $magicSchool->save();

        $spell = Spell::from5eJson($this->dataAnimateDead());
        $spell->refresh();

        $this->assertEquals('Animate Dead', $spell->name);
        $this->assertEquals('animate-dead', $spell->slug);
        $this->assertEquals(1, $spell->editions->count());

        /** @var SpellEdition $edition */
        $edition = $spell->editions->first();

        /**
         * Casting time.
         */
        $this->assertEquals(1, $edition->castingTimes->count());
        $this->assertEquals(1, $edition->castingTime->number);
        $this->assertEquals(TimeUnit::MINUTE, $edition->castingTime->unit);

        /**
         * Range.
         */
        $this->assertEquals(10, $edition->range->number);
        $this->assertEquals(DistanceUnit::FOOT, $edition->range->unit);

        /**
         * Duration.
         */
        $this->assertEquals(TimeUnit::INSTANTANEOUS, $edition->duration->unit);

        /**
         * Text entries.
         */
        $this->assertEquals(4, $edition->entries->count());
    }

    public function dataAnimateDead(): array
    {
        return [
            'name' => 'Animate Dead',
            'source' => 'PHB',
            'page' => 212,
            'srd' => true,
            'reprintedAs' => ['Animate Dead|XPHB'],
            'level' => 3,
            'school' => 'N',
            'time' => [
                [
                    'number' => 1,
                    'unit' => 'minute'
                ]
            ],
            'range' => [
                'type' => 'point',
                'distance' => [
                    'type' => 'feet',
                    'amount' => 10,
                ]
            ],
            'components' => [
                'v' => true,
                's' => true,
                'm' => 'a drop of blood, a piece of flesh, and a pinch of bone dust'
            ],
            'duration' => [
                [
                    'type' => 'instant',
                ]
            ],
            'entries' => [
                "This spell creates an undead servant. Choose a pile of bones or a corpse of a Medium or Small humanoid within range. Your spell imbues the target with a foul mimicry of life, raising it as an undead creature. The target becomes a {@creature skeleton} if you chose bones or a {@creature zombie} if you chose a corpse (the DM has the creature's game statistics).",
                "On each of your turns, you can use a bonus action to mentally command any creature you made with this spell if the creature is within 60 feet of you (if you control multiple creatures, you can command any or all of them at the same time, issuing the same command to each one). You decide what action the creature will take and where it will move during its next turn, or you can issue a general command, such as to guard a particular chamber or corridor. If you issue no commands, the creature only defends itself against hostile creatures. Once given an order, the creature continues to follow it until its task is complete.",
                "The creature is under your control for 24 hours, after which it stops obeying any command you've given it. To maintain control of the creature for another 24 hours, you must cast this spell on the creature again before the current 24-hour period ends. This use of the spell reasserts your control over up to four creatures you have animated with this spell, rather than animating a new one."
            ],
            'entriesHigherLevel' => [
                [
                    'type' => 'entries',
                    'name' => 'At Higher Levels',
                    'entries' => [
                        "When you cast this spell using a spell slot of 4th level or higher, you animate or reassert control over two additional undead creatures for each slot level above 3rd. Each of the creatures must come from a different corpse or pile of bones."
                    ]
                ]
            ],
            'affectsCreatureType' => [
                'humanoid'
            ],
            'miscTags' => [
                'PRM',
                'SMN',
                'UBA'
            ]
        ];
    }
}
