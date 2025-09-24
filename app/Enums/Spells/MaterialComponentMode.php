<?php

declare(strict_types=1);

namespace App\Enums\Spells;

enum MaterialComponentMode: int
{
    case AND = 1;
    case OR = 2;

    public function toString(): string
    {
        return match ($this) {
            self::AND => 'and',
            self::OR => 'or',
        };
    }
}
