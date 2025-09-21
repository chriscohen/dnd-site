<?php

declare(strict_types=1);

namespace App\Enums;

enum Binding: int
{
    case HARDCOVER = 1;
    case PAPERBACK = 2;
    case BOXED_SET = 3;

    public function toString(): string
    {
        return match ($this->value) {
            1 => 'hardcover',
            2 => 'paperback',
            3 => 'boxed set',
        };
    }

    public static function tryFromString(string $value): ?Binding
    {
        return match (mb_strtoupper($value)) {
            'BOXED_SET' => Binding::BOXED_SET,
            'HARDCOVER' => self::HARDCOVER,
            'PAPERBACK' => self::PAPERBACK,
            default => null,
        };
    }
}
