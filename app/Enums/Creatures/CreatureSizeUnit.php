<?php

namespace App\Enums\Creatures;

enum CreatureSizeUnit: int
{
    case TINY = 1;
    case SMALL = 2;
    case MEDIUM = 3;
    case LARGE = 4;
    case HUGE = 5;
    case GARGANTUAN = 6;

    public function toString(): string
    {
        return match ($this) {
            self::TINY => 'Tiny',
            self::SMALL => 'Small',
            self::MEDIUM => 'Medium',
            self::LARGE => 'Large',
            self::HUGE => 'Huge',
            self::GARGANTUAN => 'Gargantuan',
        };
    }

    public function toStringShort(): string
    {
        return match ($this) {
            self::TINY => 'T',
            self::SMALL => 'S',
            self::MEDIUM => 'M',
            self::LARGE => 'L',
            self::HUGE => 'H',
            self::GARGANTUAN => 'G',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            't', 'tiny' => self::TINY,
            's', 'small' => self::SMALL,
            'm', 'medium' => self::MEDIUM,
            'l', 'large' => self::LARGE,
            'h', 'huge' => self::HUGE,
            'g', 'gargantuan' => self::GARGANTUAN,
            default => null,
        };
    }
}
