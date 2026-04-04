<?php

declare(strict_types=1);

namespace App\Enums\Alignment;

enum AlignmentGoodEvil: int
{
    case GOOD = 1;
    case NEUTRAL = 2;
    case EVIL = 3;
    case ANY = 4;

    public function toString(): string
    {
        return match ($this) {
            self::GOOD => 'good',
            self::NEUTRAL => 'neutral',
            self::EVIL => 'evil',
            self::ANY => 'any',
        };
    }

    public function toStringShort(): string
    {
        return mb_strtolower($this->toString()[0]);
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'g', 'good' => self::GOOD,
            'n', 'neutral' => self::NEUTRAL,
            'e', 'evil' => self::EVIL,
            'a', 'any' => self::ANY,
            default => null,
        };
    }
}
