<?php

declare(strict_types=1);

namespace App\Enums\Sources;

enum SourceContentsType: int
{
    case CHAPTER = 1;
    case APPENDIX = 2;

    public function toString(): string
    {
        return match ($this) {
            self::CHAPTER => 'chapter',
            self::APPENDIX => 'appendix',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'chapter' => self::CHAPTER,
            'appendix' => self::APPENDIX,
            default => null,
        };
    }
}
