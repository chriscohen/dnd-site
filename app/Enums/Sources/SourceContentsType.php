<?php

declare(strict_types=1);

namespace App\Enums\Sources;

enum SourceContentsType: int
{
    case CHAPTER = 1;
    case APPENDIX = 2;
    case PART = 3;
    case LEVEL = 4;
    case EPISODE = 5;

    public function toString(): string
    {
        return match ($this) {
            self::CHAPTER => 'chapter',
            self::APPENDIX => 'appendix',
            self::PART => 'part',
            self::LEVEL => 'level',
            self::EPISODE => 'episode',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'chapter' => self::CHAPTER,
            'appendix' => self::APPENDIX,
            'part' => self::PART,
            'level' => self::LEVEL,
            'episode' => self::EPISODE,
            default => null,
        };
    }
}
