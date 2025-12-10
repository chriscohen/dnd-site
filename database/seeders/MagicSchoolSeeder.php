<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Magic\MagicSchool;
use App\Models\Media;

class MagicSchoolSeeder extends AbstractYmlSeeder
{
    protected string $path = 'magic-schools.json';
    protected string $model = MagicSchool::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new MagicSchool();
            $item->id = $datum['id'];
            $item->name = $datum['name'];

            $item->description = $datum['description'] ?? null;
            $item->parentId = $datum['parent'] ?? null;
            $item->shortName = $datum['short_name'] ?? null;

            // If there's no parent, it's a subschool, so there's no image.
            if (empty($datum['parent'])) {
                $media = Media::createFromExisting([
                    'filename' => '/magic-schools/' . $datum['id'] . '.webp',
                    'disk' => 's3',
                    'collection_name' => 'magic-schools',
                ]);
                $item->image()->associate($media);
            }
            $item->save();
        }
    }
}
