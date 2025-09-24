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
            static::LORE => 'LORE',
        };
    }

    public static function tryFromString(string $value): ?SourcebookType
    {
        return match (mb_strtoupper($value)) {
            'CORE' => self::CORE,
            'ADVENTURE' => self::ADVENTURE,
            'BOXED_SET' => self::BOXED_SET,
            'CHARACTER_OPTIONS' => self::CHARACTER_OPTIONS,
            'GEAR_MAGIC_ITEMS' => self::GEAR_MAGIC_ITEMS,
            'MONSTERS' => self::MONSTERS,
            'SPELLS' => self::SPELLS,
            'CAMPAIGN_SETTING' => self::CAMPAIGN_SETTING,
            'LORE' => self::LORE,
            default => null,
        };
    }
}
