<?php

declare(strict_types=1);

namespace App\Enums\Prerequisites;

use InvalidArgumentException;

enum CraftType: int
{
    case BOWMAKING = 1;

    public function toString(): string
    {
        return match ($this) {
            self::BOWMAKING => 'bowmaking',
        };
    }

    public static function tryFromString(string $value, bool $throws = false): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'bowmaking' => self::BOWMAKING,
            default => $throws ?
                throw new InvalidArgumentException('"' . $value . '" is not a valid CraftType') :
                null,
        };
    }
}
