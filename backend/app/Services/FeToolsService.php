<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CharacterClasses\CharacterClass;
use App\Models\Creatures\CreatureType;
use App\Models\Feats\Feature;
use App\Models\Items\ItemType;
use App\Models\Language;
use App\Models\Sources\Source;
use App\Models\Spells\Spell;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class FeToolsService
{
    protected static ?array $spellSources;

    /**
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     */
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

    /**
     * Map JSON keys in 5e.tools data, to model classes.
     */
    public static function types(): array
    {
        return [
            'book' => Source::class,
            'class' => CharacterClass::class,
            'item' => ItemType::class,
            'language' => Language::class,
            'monster' => CreatureType::class,
            'monsterfeatures' => Feature::class,
            'object' => ItemType::class,
            'race' => CreatureType::class,
            'spell' => Spell::class,
        ];
    }
}
