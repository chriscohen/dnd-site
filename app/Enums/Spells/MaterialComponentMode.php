<?php

declare(strict_types=1);

namespace App\Enums\Spells;

use InvalidArgumentException;

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

    public static function tryFromString(string $value, bool $throw = false): self
    {
        return match (mb_strtolower($value)) {
            'and' => self::AND,
            'or' => self::OR,
            default => $throw ?
                throw new InvalidArgumentException('"' . $value . '" is not a valid material component mode.') :
                null,
        };
    }
}
