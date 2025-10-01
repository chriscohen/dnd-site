<?php

declare(strict_types=1);

namespace App\Enums\Prerequisites;

use InvalidArgumentException;

enum WeaponFocusType: int
{
    case LONGBOW = 1;

    public function toString(): string
    {
        return match ($this) {
            self::LONGBOW => 'longbow',
        };
    }

    public static function tryFromString(string $value, bool $throw = false): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'longbow' => self::LONGBOW,
            default => $throw ?
                throw new InvalidArgumentException('"' . $value . '" is not a valid WeaponFocusType') :
                null,
        };
    }
}
