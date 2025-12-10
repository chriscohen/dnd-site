<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;

class FeatSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'feats';
    protected string $model = Feat::class;

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            $item = new Feat();
            $item->id = $datum['id'];
            $item->name = $datum['name'];
            print $item->name . "\n";
            $item->slug = $datum['slug'] ?? $this->makeSlug($datum['name']);

            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new FeatEdition();
                $edition->feat()->associate($item);
                $edition->id = $editionData['id'];
                $edition->description = $editionData['description'] ?? null;
                $edition->gameEdition = GameEdition::tryFromString($editionData['game_edition']);

                $edition->save();

                $this->setReferences($editionData['references'] ?? [], $edition);
                $this->setPrerequisites($editionData['prerequisites'] ?? [], $edition);
            }
        }
    }
}
