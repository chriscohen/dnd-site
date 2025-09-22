<?php

declare(strict_types=1);

namespace App\Enums;

enum Binding: int
{
    case HARDCOVER = 1;
    case PAPERBACK = 2;
    case BOXED_SET = 3;
    case SOFTCOVER_AND_HARDCOVER = 4;

    public function toString(): string
    {
        return match ($this) {
            self::HARDCOVER => 'hardcover',
            self::PAPERBACK => 'paperback',
            self::BOXED_SET => 'boxed set',
            self::SOFTCOVER_AND_HARDCOVER => 'softcover and hardcover',
        };
    }

    public static function tryFromString(string $value): ?Binding
    {
        return match (mb_strtoupper($value)) {
            'BOXED_SET' => Binding::BOXED_SET,
            'HARDCOVER' => self::HARDCOVER,
            'PAPERBACK' => self::PAPERBACK,
            'SOFTCOVER_AND_HARDCOVER' => self::SOFTCOVER_AND_HARDCOVER,
            default => null,
        };
    }
}
