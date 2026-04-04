<?php

declare(strict_types=1);

namespace App\Enums;

enum AreaType: int
{
    case LINE = 1;
    case CUBE = 2;
    case CONE = 3;
    case SPHERE = 4;
    case CYLINDER = 5;
    case CONTIGUOUS_SQUARE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::LINE => 'LINE',
            self::CUBE => 'CUBE',
            self::CONE => 'CONE',
            self::SPHERE => 'SPHERE',
            self::CONTIGUOUS_SQUARE => 'CONTIGUOUS_SQUARE',
            self::CYLINDER => 'CYLINDER',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtoupper($value)) {
            'LINE' => self::LINE,
            'CUBE' => self::CUBE,
            'CONE' => self::CONE,
            'SPHERE' => self::SPHERE,
            'CYLINDER' => self::CYLINDER,
            'CONTIGUOUS_SQUARE', 'SQUARE' => self::CONTIGUOUS_SQUARE,
            default => null,
        };
    }
}
