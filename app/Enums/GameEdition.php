<?php

declare(strict_types=1);

namespace App\Enums;

enum GameEdition: int
{
    case FIRST = 1;
    case SECOND = 2;
    case TPF = 4;
    case THIRD = 5;
    case FOURTH = 6;
    case FIFTH = 7;
    case FIFTH_REVISED = 8;

    public function toString($short = false): string
    {
        if ($short) {
            return match ($this->name) {
                'FIRST' => '1e',
                'SECOND' => '2e',
                'THIRD' => '3e',
                'TPF' => '3.5',
                'FOURTH' => '4e',
                'FIFTH' => '5e (2014)',
                'FIFTH_REVISED' => '5e (2024)',
            };
        } else {
            return match ($this->name) {
                'FIRST' => 'First Edition',
                'SECOND' => 'AD&D Second Edition',
                'THIRD' => 'Third Edition',
                'TPF' => '3.5 Edition',
                'FOURTH' => 'Fourth Edition',
                'FIFTH' => 'Fifth Edition (2014)',
                'FIFTH_REVISED' => 'Fifth Edition (2024)',
            };
        }
    }

    public function toStringShort(): string
    {
        return $this->toString(true);
    }

    public static function tryFromString(string $value): ?GameEdition
    {
        return match (mb_strtolower($value)) {
            '1st', '1e', 'first' => self::FIRST,
            '2nd', '2e', 'second' => self::SECOND,
            '3rd', '3e', 'third' => self::THIRD,
            '3.5', '3.5e' => self::TPF,
            '4th', '4e', 'fourth' => self::FOURTH,
            '5th', '5e', 'fifth', '5.14', '5.2014', '5e (2014)' => self::FIFTH,
            '5e (2024)', '5.24', '5.2024' => self::FIFTH_REVISED,
            default => null
        };
    }
}
