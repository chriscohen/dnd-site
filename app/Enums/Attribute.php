<?php

declare(strict_types=1);

namespace App\Enums;

enum Attribute: int
{
    case STR = 1;
    case DEX = 2;
    case CON = 3;
    case INT = 4;
    case WIS = 5;
    case CHA = 6;

    public function toString(): string
    {
        return match ($this->name) {
            'STR' => 'Strength',
            'DEX' => 'Dexterity',
            'CON' => 'Constitution',
            'INT' => 'Intelligence',
            'WIS' => 'Wisdom',
            'CHA' => 'Charisma',
        };
    }

    public function toStringShort(): string
    {
        return $this->name;
    }

    public static function tryFromString(string $value): Attribute
    {
        return match ($value) {
            'STR' => self::STR,
            'DEX' => self::DEX,
            'CON' => self::CON,
            'INT' => self::INT,
            'WIS' => self::WIS,
            'CHA' => self::CHA,
        };
    }
}
