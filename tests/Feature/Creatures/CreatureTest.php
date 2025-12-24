<?php

declare(strict_types=1);

namespace Tests\Feature\Creatures;

use App\Enums\GameEdition;
use App\Models\Creatures\CreatureMajorType;
use App\Models\Sources\Source;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use Tests\TestCase;
use App\Models\Creatures\Creature;

final class CreatureTest extends TestCase
{
    public function testImportFactolSkull(): void
    {
        // Create the "undead" creature type.
        CreatureMajorType::create([
            'slug' => 'undead',
            'name' => 'Undead',
            'plural' => 'Undead',
        ]);

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
            ] as $conditionName
        ) {
            $condition = new StatusCondition();
            $condition->id = $this->faker->uuid();
            $condition->slug = $conditionName;
            $condition->name = $conditionName;
            $condition->save();
            $edition = new StatusConditionEdition();
            $edition->statusCondition()->associate($condition);
            $edition->game_edition = GameEdition::FIFTH_REVISED;
            $edition->save();
        }

        $this->seed5eData(realpath(__DIR__) . '/../../Data/sources/adventure-atlas-the-mortuary.json', Source::class);
        $creature = Creature::from5eJson(self::dataFactolSkull());

        $this->assertEquals('Factol Skall', $creature->name);
        $this->assertEquals('factol-skall', $creature->slug);
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
