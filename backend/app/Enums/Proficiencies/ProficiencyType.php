<?php

declare(strict_types=1);

namespace App\Enums\Proficiencies;

enum ProficiencyType: int
{
    case ABILITY = 1;
    case ARMOR = 2;
    case SKILL = 3;
    case TOOL = 4;
    case WEAPON = 5;

    public function toString(): string
    {
        return match ($this) {
            self::ABILITY => 'ability',
            self::ARMOR => 'armor',
            self::SKILL => 'skill',
            self::TOOL => 'tool',
            self::WEAPON => 'weapon',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'ability' => self::ABILITY,
            'armor' => self::ARMOR,
            'skill' => self::SKILL,
            'tool' => self::TOOL,
            'weapon' => self::WEAPON,
            default => null,
        };
    }
}
