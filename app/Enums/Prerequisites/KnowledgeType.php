<?php

declare(strict_types=1);

namespace App\Enums\Prerequisites;

use InvalidArgumentException;

enum KnowledgeType: int
{
    case NATURE = 1;
    case LOCAL = 2;
    case DUNGEONEERING = 3;
    case HISTORY = 4;
    case RELIGION = 5;

    public function toString(): string
    {
        return match ($this) {
            self::NATURE => 'nature',
            self::LOCAL => 'local',
            self::DUNGEONEERING => 'dungeoneering',
            self::HISTORY => 'history',
            self::RELIGION => 'religion',
        };
    }

    public static function tryFromString(string $value, bool $throws = false): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'nature' => self::NATURE,
            'local' => self::LOCAL,
            'dungeoneering' => self::DUNGEONEERING,
            'history' => self::HISTORY,
            'religion' => self::RELIGION,
            default => $throws ?
                throw new InvalidArgumentException('"' . $value . '" is not a valid KnowledgeType') :
                null,
        };
    }
}
