<?php

declare(strict_types=1);

namespace App\Enums;

enum Alignment: int
{
    case LAWFUL_GOOD = 1;
    case LAWFUL_NEUTRAL = 2;
    case LAWFUL_EVIL = 3;
    case NEUTRAL_GOOD = 4;
    case NEUTRAL = 5;
    case NEUTRAL_EVIL = 6;
    case CHAOTIC_GOOD = 7;
    case CHAOTIC_NEUTRAL = 8;
    case CHAOTIC_EVIL = 9;

    case ANY_GOOD = 10;
    case ANY_NEUTRAL = 11;
    case ANY_EVIL = 12;

    case ANY_LAWFUL = 13;
    case ANY_CHAOTIC = 14;

    case ANY = 15;

    public function toString(): string
    {
        return match ($this) {
            self::LAWFUL_GOOD => 'lawful good',
            self::LAWFUL_NEUTRAL => 'lawful neutral',
            self::LAWFUL_EVIL => 'lawful evil',
            self::NEUTRAL_GOOD => 'neutral good',
            self::NEUTRAL => 'neutral',
            self::NEUTRAL_EVIL => 'neutral evil',
            self::CHAOTIC_GOOD => 'chaotic good',
            self::CHAOTIC_NEUTRAL => 'chaotic neutral',
            self::CHAOTIC_EVIL => 'chaotic evil',
            self::ANY_GOOD => 'any good',
            self::ANY_NEUTRAL => 'any neutral',
            self::ANY_EVIL => 'any evil',
            self::ANY_LAWFUL => 'any lawful',
            self::ANY_CHAOTIC => 'any chaotic',
            self::ANY => 'any',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace('_', ' ', mb_strtolower($value))) {
            'lawful good' => self::LAWFUL_GOOD,
            'lawful neutral' => self::LAWFUL_NEUTRAL,
            'lawful evil' => self::LAWFUL_EVIL,
            'neutral good' => self::NEUTRAL_GOOD,
            'neutral' => self::NEUTRAL,
            'neutral evil' => self::NEUTRAL_EVIL,
            'chaotic good' => self::CHAOTIC_GOOD,
            'chaotic neutral' => self::CHAOTIC_NEUTRAL,
            'chaotic evil' => self::CHAOTIC_EVIL,
            'any good' => self::ANY_GOOD,
            'any neutral' => self::ANY_NEUTRAL,
            'any evil' => self::ANY_EVIL,
            'any lawful' => self::ANY_LAWFUL,
            'any chaotic' => self::ANY_CHAOTIC,
            'any' => self::ANY,
            default => null,
        };
    }
}
