<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CharacterClass;
use App\Models\Media;

class CharacterClassSeeder extends AbstractYmlSeeder
{
    protected string $path = 'character-classes.json';
    protected string $model = CharacterClass::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new CharacterClass();
            $item->id = $datum['id'];
            $item->slug = $datum['slug'] ?? $this->makeSlug($datum['name']);
            $item->name = $datum['name'];
            $item->is_prestige = $datum['is_prestige'] ?? false;

            if (!empty($datum['image'])) {
                $media = Media::createFromExisting([
                    'filename' => '/classes/' . $datum['image'],
                    'disk' => 's3',
                    'collection_name' => 'classes',
                ]);
                $item->image()->associate($media);
            }

            $item->save();

            $this->setPrerequisites($datum['prerequisites'] ?? [], $item);
            $this->setReferences($datum['references'] ?? [], $item);
        }
    }
}
