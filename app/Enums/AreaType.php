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

    public function toString(): string
    {
        return match ($this) {
            self::LINE => 'LINE',
            self::CUBE => 'CUBE',
            self::CONE => 'CONE',
            self::SPHERE => 'SPHERE',
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
            default => null,
        };
    }
}
