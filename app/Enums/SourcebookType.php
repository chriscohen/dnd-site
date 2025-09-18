<?php

declare(strict_types=1);

namespace App\Enums;

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
        return mb_ucfirst(mb_strtolower($this->name));
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
