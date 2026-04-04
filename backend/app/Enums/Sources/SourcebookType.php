<?php

declare(strict_types=1);

namespace App\Enums\Sources;

enum SourcebookType: int
{
    case CORE = 1;
    case ADVENTURE = 2;
    case BOXED_SET = 3;
    case CHARACTER_OPTIONS = 4;
    case GEAR_MAGIC_ITEMS = 5;
    case MONSTERS = 6;
    case SPELLS = 7;
    case CAMPAIGN_SETTING = 8;
    case LORE = 9;
    case ENCOUNTERS = 10;
    case SUPPLEMENT = 11;
    case ORGANIZED_PLAY = 12;
    case SCREEN = 13;
    case OTHER = 99;

    public function toString(): string
    {
        return match ($this) {
            self::CORE => 'Core Rulebook',
            self::ADVENTURE => 'Adventure',
            self::BOXED_SET => 'Boxed Set',
            self::CHARACTER_OPTIONS => 'Character Options',
            self::GEAR_MAGIC_ITEMS => 'Gear & Magic Items',
            self::MONSTERS => 'Monsters',
            self::SPELLS => 'Spells',
            self::CAMPAIGN_SETTING => 'Campaign Setting',
            self::LORE => 'Lore',
            self::ENCOUNTERS => 'Encounters',
            self::SUPPLEMENT => 'Supplement',
            self::ORGANIZED_PLAY => 'Organized Play',
            self::SCREEN => 'Screen',
            self::OTHER => 'Other'
        };
    }

    public static function tryFromString(string $value): ?SourcebookType
    {
        return match (str_replace("_", "", mb_strtolower($value))) {
            'core' => self::CORE,
            'adventure' => self::ADVENTURE,
            'boxedset' => self::BOXED_SET,
            'characteroptions' => self::CHARACTER_OPTIONS,
            'gearmagicitems' => self::GEAR_MAGIC_ITEMS,
            'monsters' => self::MONSTERS,
            'spells' => self::SPELLS,
            'setting', 'setting-alt', 'campaignsetting' => self::CAMPAIGN_SETTING,
            'lore' => self::LORE,
            'encounters' => self::ENCOUNTERS,
            'supplement', 'supplement-alt' => self::SUPPLEMENT,
            'organized-play', 'organizedplay' => self::ORGANIZED_PLAY,
            'screen' => self::SCREEN,
            'other' => self::OTHER,
            default => null,
        };
    }
}
