<?php

declare(strict_types=1);

namespace App\Enums\Alignment;

enum AlignmentLawChaos: int
{
    case LAWFUL = 1;
    case NEUTRAL = 2;
    case CHAOTIC = 3;
    case ANY = 4;

    public function toString(): string
    {
        return match ($this) {
            self::LAWFUL => 'lawful',
            self::NEUTRAL => 'neutral',
            self::CHAOTIC => 'chaotic',
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
            'l', 'lawful' => self::LAWFUL,
            'n', 'neutral' => self::NEUTRAL,
            'c', 'chaotic' => self::CHAOTIC,
            'a', 'any' => self::ANY,
            default => null,
        };
    }
}
