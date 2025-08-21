<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Items\Item;
use App\Models\Items\ItemEdition;
use App\Models\Source;

class ItemSeeder extends AbstractYmlSeeder
{
    protected string $path = 'items.json';
    protected string $model = Item::class;
    protected array $excludedProperties = [
        'cover_image'
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new Item();
            $item->id = $datum['id'];
            // If no slug, assume we can just urlencode the name.
            $item->slug = $datum['slug'] ?? urlencode(mb_strtolower($datum['name']));
            $item->name = $datum['name'];
            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new ItemEdition();
                $edition->id = $editionData['id'];
                $edition->item_id = $datum['id'];
                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->description = $editionData['description'];
                $edition->price = $editionData['price'] ?? null;
                $edition->quantity = $editionData['quantity'] ?? 1;
                $edition->weight = $editionData['weight'] ?? null;
                $edition->source_id = Source::query()->where('slug', $editionData['source'])->firstOrFail()->id;

                $edition->save();
                $item->editions->add($edition);
            }
        }
    }
}
