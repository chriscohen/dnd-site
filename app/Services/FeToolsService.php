<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class FeToolsService
{
    protected static ?array $spellSources;

    public static function loadSpellSources(): bool
    {
        if (!empty(static::$spellSources)) {
            return false;
        }

        if (Storage::disk('data')->missing('5etools/spell-sources.json')) {
            throw new FileNotFoundException('storage/data/5etools/spell-sources.json not found');
        }

        static::$spellSources = json_decode(Storage::disk('data')->get('5etools/spell-sources.json'), true);
        return true;
    }

    /**
     * @return array{
     *     string: string[],
     * }
     * @throws FileNotFoundException
     */
    public static function getClassesForSpell(string $spellName): array
    {
        static::loadSpellSources();

        $classData = static::findSpellSource($spellName);

        if (empty($classData) || empty($classData['class'])) {
            throw new \InvalidArgumentException('No classes found for spell ' . $spellName);
        }

        $output = [];

        /** @var array{name: string, source: string} $item */
        foreach ($classData['class'] as $item) {
            // Restructure the array so no class names are duplicated, and we list all the sources, for each class, as
            // an array.
            $output[$item['name']][] = $item['source'];
        };

        return $output;
    }

    public static function findSpellSource(string $spellName): ?array
    {
        foreach (static::$spellSources as $sourceData) {
            if (!empty($sourceData[$spellName])) {
                return $sourceData[$spellName];
            }
        }

        return null;
    }
}
