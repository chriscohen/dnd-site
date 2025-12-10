<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Category;
use App\Models\Creatures\CreatureMajorType;
use App\Models\Creatures\CreatureMajorTypeEdition;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;

class CreatureMajorTypeSeeder extends AbstractYmlSeeder
{
    protected string $path = 'creature-major-types.json';
    protected string $model = Category::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new CreatureMajorType();

            $item->id = $datum['id'];
            $item->name = $datum['name'];
            $item->slug = $datum['slug'];
            $item->plural = $datum['plural'];
            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new CreatureMajorTypeEdition();
                $edition->description = $editionData['description'];
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition'])->value;
                $edition->creatureMajorType()->associate($item);
                $edition->save();
            }
        }
    }
}
