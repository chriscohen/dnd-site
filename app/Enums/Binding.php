<?php

declare(strict_types=1);

namespace App\Enums;

enum Binding: int
{
    case HARDCOVER = 1;
    case PAPERBACK = 2;

    public function toString(): string
    {
        return match ($this->value) {
            1 => 'hardcover',
            2 => 'paperback',
        };
    }

    public static function tryFromString(string $value): ?Binding
    {
        return match (mb_strtoupper($value)) {
            'HARDCOVER' => self::HARDCOVER,
            'PAPERBACK' => self::PAPERBACK,
            default => null,
        };
    }
}
