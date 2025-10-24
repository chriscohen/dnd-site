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

    public function toString(): string
    {
        return match ($this) {
            static::CORE => 'Core Rulebook',
            static::ADVENTURE => 'Adventure',
            static::BOXED_SET => 'Boxed Set',
            static::CHARACTER_OPTIONS => 'Character Options',
            static::GEAR_MAGIC_ITEMS => 'Gear & Magic Items',
            static::MONSTERS => 'Monsters',
            static::SPELLS => 'Spells',
            static::CAMPAIGN_SETTING => 'Campaign Setting',
            static::LORE => 'Lore',
            static::ENCOUNTERS => 'Encounters'
        };
    }

    public static function tryFromString(string $value): ?SourcebookType
    {
        return match (str_replace("_", " ", mb_strtolower($value))) {
            'core' => self::CORE,
            'adventure' => self::ADVENTURE,
            'boxed set' => self::BOXED_SET,
            'character options' => self::CHARACTER_OPTIONS,
            'gear magic items' => self::GEAR_MAGIC_ITEMS,
            'monsters' => self::MONSTERS,
            'spells' => self::SPELLS,
            'campaign setting' => self::CAMPAIGN_SETTING,
            'lore' => self::LORE,
            'encounters' => self::ENCOUNTERS,
            default => null,
        };
    }
}
