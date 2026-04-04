<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaType: int
{
    case IMAGE = 1;
    case VIDEO = 2;
    case TOKEN = 3;

    public function toString(): string
    {
        return match ($this) {
            self::IMAGE => 'image',
            self::VIDEO => 'video',
            self::TOKEN => 'token',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match ($value) {
            'image' => self::IMAGE,
            'video' => self::VIDEO,
            'token' => self::TOKEN,
            default => null,
        };
    }
}
