<?php

declare(strict_types=1);

namespace Tests\Feature\Creatures;

use App\Enums\AbilityScoreType;
use App\Enums\Conditions\ConditionType;
use App\Enums\Creatures\CreatureSizeUnit;
use App\Enums\Damage\DamageType;
use App\Enums\GameEdition;
use App\Enums\Movement\MovementType;
use App\Enums\Units\DistanceUnit;
use App\Models\ArmorClass\ArmorClass;
use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;
use App\Models\Creatures\CreatureMainType;
use App\Models\Reference;
use App\Models\Skills\Skill;
use App\Models\Skills\SkillEdition;
use App\Models\Sources\Source;
use App\Models\Conditions\Condition;
use App\Models\Conditions\ConditionEdition;
use Tests\FeatureTestCase;

final class CreatureFeatureTest extends FeatureTestCase
{
    public function testImportFactolSkull(): void
    {
        // Create the "undead" creature type.
        CreatureMainType::create([
            'slug' => 'undead',
            'name' => 'Undead',
            'plural' => 'Undead',
        ]);

        // Create the skills we will need.
        foreach (
            [
                'arcana' => AbilityScoreType::INT,
                'history' => AbilityScoreType::INT,
                'medicine' => AbilityScoreType::WIS,
                'perception' => AbilityScoreType::WIS,
            ] as $skillName => $abilityType
        ) {
            $skill = new Skill();
            $skill->id = $this->faker->uuid;
            $skill->name = $skillName;
            $skill->slug = $skill::makeSlug($skillName);
            $skill->save();

            $skillEdition = new SkillEdition();
            $skillEdition->skill()->associate($skill);
            $skillEdition->game_edition = GameEdition::FIFTH_REVISED;
            $skillEdition->related_ability = $abilityType;
            $skillEdition->save();
        }

        // Create the status conditions we will need.
        foreach (
            [
                'blinded',
                'charmed',
                'deafened',
                'exhaustion',
                'frightened',
                'paralyzed',
                'petrified',
                'poisoned',
                'stunned'
            ] as $conditionName
        ) {
            $condition = new Condition();
            $condition->id = $this->faker->uuid();
            $condition->slug = $conditionName;
            $condition->name = $conditionName;
            $condition->type = ConditionType::STATUS_CONDITION;
            $condition->save();
            $edition = new ConditionEdition();
            $edition->condition()->associate($condition);
            $edition->game_edition = GameEdition::FIFTH_REVISED;
            $edition->save();
        }

        $this->seed5eData(realpath(__DIR__) . '/../../Data/sources/adventure-atlas-the-mortuary.json', Source::class);
        $creature = Creature::from5eJson(self::dataFactolSkull());
        $creature->refresh();

        $this->assertEquals('Factol Skall', $creature->name);
        $this->assertEquals('factol-skall', $creature->slug);

        $this->assertEquals(1, $creature->editions->count());
        /** @var CreatureEdition | null $edition */
        $edition = $creature->editions->first();

        /**
         * Source.
         */
        /** @var Reference $reference */
        $reference = $edition->references->first();
        $this->assertEquals('Adventure Atlas: The Mortuary', $reference->edition->source->name);

        /**
         * Abilities.
         */
        $this->assertEquals(11, $edition->str->value);
        $this->assertEquals(16, $edition->dex->value);
        $this->assertEquals(16, $edition->con->value);
        $this->assertEquals(20, $edition->int->value);
        $this->assertEquals(14, $edition->wis->value);
        $this->assertEquals(16, $edition->cha->value);

        /**
         * Ability proficiencies.
         */
        $this->assertFalse($edition->str->is_proficient);
        $this->assertFalse($edition->dex->is_proficient);
        $this->assertTrue($edition->con->is_proficient);
        $this->assertTrue($edition->int->is_proficient);
        $this->assertTrue($edition->wis->is_proficient);
        $this->assertFalse($edition->cha->is_proficient);

        /**
         * Alignment.
         */
        $this->assertEquals('NE', $edition->alignment->toStringShort());

        /**
         * Armor Class.
         */
        /** @var ArmorClass $firstAc */
        $firstAc = $edition->armorClass->first();
        $this->assertEquals(17, $firstAc->value);

        /**
         * Hit Points.
         */
        $this->assertEquals(210, $edition->hitPoints->average);
        $this->assertEquals('28d8 + 84', $edition->hitPoints->formula);

        /**
         * Sizes.
         */
        /** @var CreatureSizeUnit $firstSize */
        $firstSize = $edition->sizes->first();
        $this->assertEquals(CreatureSizeUnit::MEDIUM->value, $firstSize->value);

        /**
         * Movement Speed.
         */
        $this->assertEquals(30, $edition->getSpeed(MovementType::WALK)?->value);
        $this->assertTrue($edition->canHover());
        $this->assertEquals(30, $edition->getSpeed(MovementType::FLY)?->value);

        /**
         * Skills.
         */
        // Weird stuff regarding Laravel and loading relations - this test doesn't work.
        $this->assertTrue($edition->hasSkillExpertise('arcana'));
        $this->assertEquals(17, $edition->getSkillModifier('arcana'));
        $this->assertEquals(18, $edition->passivePerception);

        /**
         * Senses.
         */
        $truesight = $edition->truesight;
        $this->assertEquals(120, $truesight->range);
        $this->assertEquals(DistanceUnit::FOOT, $truesight->distance_unit);

        /**
         * Type.
         */
        $this->assertEquals('undead', $edition->type->majorType->slug);

        /**
         * Condition immunities.
         */
        $condition1 = Condition::query()->where('slug', 'charmed')->firstOrFail();
        /** @var ConditionEdition $conditionEdition1 */
        $conditionEdition1 = $condition1->editions->firstWhere('game_edition', $edition->game_edition);
        $this->assertTrue($edition->isImmuneTo($conditionEdition1));

        $condition2 = Condition::query()->where('slug', 'deafened')->firstOrFail();
        /** @var ConditionEdition $conditionEdition2 */
        $conditionEdition2 = $condition2->editions->firstWhere('game_edition', $edition->game_edition);
        $this->assertFalse($edition->isImmuneTo($conditionEdition2));

        /**
         * Damage Immunities.
         */
        $this->assertTrue($edition->isImmuneTo(DamageType::NECROTIC));
        $this->assertFalse($edition->isImmuneTo(DamageType::ACID));
    }

    public static function dataFactolSkull(): array
    {
        return [
            'name' => 'Factol Skall',
            'source' => 'AATM',
            'size' => ['M'],
            'type' => [
                'type' => 'undead',
                'tags' => ['wizard']
            ],
            'alignment' => ['N', 'E'],
            'ac' => [
                [
                    'ac' => 17,
                    'from' => ['natural armor']
                ]
            ],
            'hp' => [
                'average' => 210,
                'formula' => '28d8 + 84'
            ],
            'speed' => [
                'walk' => 30,
                'fly' => [
                    'number' => 30,
                    'condition' => '(hover)'
                ],
                'canHover' => true,
            ],
            'str' => '11',
            'dex' => '16',
            'con' => '16',
            'int' => '20',
            'wis' => '14',
            'cha' => '16',
            'save' => [
                'con' => '+9',
                'int' => '+11',
                'wis' => '+8',
            ],
            'skill' => [
                'arcana' => '+17',
                'history' => '+11',
                'medicine' => '+8',
                'perception' => '+8',
            ],
            'senses' => ['truesight 120 ft.'],
            'passive' => 18,
            'resist' => [
                'cold',
                [
                    'resist' => [
                        'bludgeoning',
                        'piercing',
                        'slashing',
                    ],
                    'note' => 'from nonmagical attacks',
                    'cond' => true,
                ]
            ],
            'immune' => [
                'necrotic',
                'poison',
            ],
            'conditionImmune' => [
                'charmed',
                'exhaustion',
                'frightened',
                'paralyzed',
                'poisoned',
                'stunned',
            ],
            'languages' => [
                'all'
            ],
            'cr' => '17',
            'spellcasting' => [
                [
                    'name' => 'Spellcasting',
                    'type' => 'spellcasting',
                    'headerEntries' => [
                        'Skall casts one of the following spells, requiring no material components and using Intelligence as the spellcasting ability (spell save {@dc 19}):',
                    ],
                    'will' => [
                        '{@spell detect magic}',
                        '{@spell mage hand}',
                        '{@spell prestidigitation}',
                    ],
                    'daily' => [
                        '2e' => [
                            '{@spell animate dead} (as an action)',
                            '{@spell dispel magic}',
                            '{@spell speak with dead}',
                        ],
                        '1e' => [
                            '{@spell finger of death}',
                            '{@spell plane shift} (self only)',
                            '{@spell project image}',
                        ]
                    ],
                    'ability' => 'int',
                    'displayAs' => 'action'
                ]
            ],
            'trait' => [
                [
                    'name' => 'Aura of Death',
                    'entries' => ['Creatures within 30 feet of Skall have disadvantage on death saving throws.']
                ],
                [
                    'name' => 'Cosmic Annihilation',
                    'entries' => ['A creature killed by Skall can be restored to life only by means of a true resurrection or {@spell wish} spell.']
                ]
            ],
            'action' => [
                [
                    'name' => 'Multiattack',
                    'entries' => ['Skall makes one Withering Touch attack or uses Spellcasting. He also uses Death Knell twice.']
                ]
            ],
            'reactionHeader' => [
                'Skall can take up to three reactions per round but only one per turn.',
            ],
            'reaction' => [
                [
                    'Baleful Counterspell',
                    'entries' => ['Skall chatters his teeth to interrupt a creature he can see within 60 feet of himself that is casting a spell. If the spell is 4th level or lower, it fails and has no effect. If the spell is 5th level or higher, Skall makes an Intelligence check ({@dc 10} + the spell\'s level). On a successful check, the spell fails and has no effect. Whatever the spell\'s level, the caster takes 10 ({@damage 3d6}) necrotic damage if the spell fails.']
                ]
            ],
            'traitTags' => [
                'Legendary Resistances',
                'Rejuvenation',
                'Turn Resistance',
            ],
            'senseTags' => ['U'],
            'actionTags' => ['Multiattack'],
            'languageTags' => ['XX'],
            'damageTags' => ['C', 'I', 'N', 'Y'],
            'damageTagsSpell' => ['N'],
            'spellcastingTags' => ['O'],
            'miscTags' => ['AOE'],
            'conditionInflict' => ['frightened'],
            'conditionInflictSpell' => [
                'blinded',
                'deafened'
            ],
            'savingThrowForced' => [
                'constitution',
                'wisdom',
            ],
            'savingThrowForcedSpell' => [
                'charisma',
                'constitution',
            ],
            'hasToken' => true,
            'hasFluff' => true,
            'hasFluffImages' => true,
        ];
    }
}
