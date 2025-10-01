<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;

class FeatSeeder extends AbstractYmlSeeder
{
    protected string $path = 'feats.json';
    protected string $model = Feat::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new Feat();
            $item->id = $datum['id'];
            $item->name = $datum['name'];
            $item->slug = $datum['slug'] ?? $this->makeSlug($datum['name']);

            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new FeatEdition();
                $edition->feat()->associate($item);
                $edition->id = $editionData['id'];
                $edition->description = $editionData['description'] ?? null;
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);

                $edition->save();

                $this->setReferences($editionData['references'] ?? [], $edition);
                $this->setPrerequisites($editionData['prerequisites'] ?? [], $edition);
            }
        }
    }
}
