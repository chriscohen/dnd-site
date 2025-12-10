<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\CharacterClasses\CharacterClassEdition;
use App\Models\Media;

class CharacterClassSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'character-classes';
    protected string $model = CharacterClass::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            $item = new CharacterClass();
            $item->id = $datum['id'];
            $item->slug = $datum['slug'] ?? $this->makeSlug($datum['name']);
            $item->name = $datum['name'];
            print $item->name . "\n";

            if (!empty($datum['image'])) {
                $media = Media::createFromExisting([
                    'filename' => '/classes/' . $datum['image'],
                    'disk' => 's3',
                    'collection_name' => 'classes',
                ]);
                $item->image()->associate($media);
            }

            $item->save();

            foreach ($datum['editions'] ?? [] as $editionData) {
                $edition = new CharacterClassEdition();
                $edition->characterClass()->associate($item);

                $edition->gameEdition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->alternateName = $editionData['alternate_name'] ?? null;
                $edition->caption = $editionData['caption'] ?? null;
                $edition->hitDieFaces = $editionData['hit_die_faces'] ?? null;
                $edition->isPrestige = $editionData['is_prestige'] ?? false;
                $edition->parentId = $editionData['parent_id'] ?? null;

                $edition->save();

                $this->setPrerequisites($editionData['prerequisites'] ?? [], $edition);
                $this->setReferences($editionData['references'] ?? [], $edition);
            }
        }
    }
}
