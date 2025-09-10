<?php

declare(strict_types=1);

namespace App\Enums;

enum JsonRenderMode: int
{
    case SHORT = 1;
    case FULL = 2;

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtoupper($value)) {
            'SHORT' => JsonRenderMode::SHORT,
            'FULL' => JsonRenderMode::FULL,
            default => null,
        };
    }
}
