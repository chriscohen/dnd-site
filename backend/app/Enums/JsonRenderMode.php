<?php

declare(strict_types=1);

namespace App\Enums;

enum JsonRenderMode: int
{
    case SHORT = 1;
    case TEASER = 2;
    case FULL = 3;

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtoupper($value)) {
            'SHORT' => JsonRenderMode::SHORT,
            'TEASER' => JsonRenderMode::TEASER,
            'FULL' => JsonRenderMode::FULL,
            default => null,
        };
    }
}
