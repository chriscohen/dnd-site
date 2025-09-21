<?php

declare(strict_types=1);

namespace App\Enums;

enum SourceContentType: int
{
    case BOOK = 1;
    case MAP = 2;
    case CARD = 3;
    case HANDOUT = 4;
    case MAP_OVERLAY = 5;

    public function toString(): string
    {
        return match ($this) {
            self::BOOK => 'book',
            self::MAP => 'map',
            self::CARD => 'card',
            self::HANDOUT => 'handout',
            self::MAP_OVERLAY => 'map overlay',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match ($value) {
            'book' => self::BOOK,
            'map' => self::MAP,
            'card' => self::CARD,
            'handout' => self::HANDOUT,
            'map_overlay' => self::MAP_OVERLAY,
            default => null,
        };
    }
}
