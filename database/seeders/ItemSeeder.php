<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Category;
use App\Models\Items\Item;
use App\Models\Items\ItemEdition;
use App\Models\Media;

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
            $item->slug = $datum['slug'] ?? self::makeSlug($datum['name']);
            $item->name = $datum['name'];

            if (!empty($datum['image'])) {
                $media = Media::createFromExisting([
                    'filename' => '/items/' . $datum['image'],
                    'disk' => 's3',
                    'collection_name' => 'spells',
                ]);
                $item->image()->associate($media);
            }

            $item->save();

            foreach ($datum['editions'] ?? [] as $editionData) {
                $edition = new ItemEdition();
                $edition->id = $editionData['id'];
                $edition->item_id = $datum['id'];

                $edition->is_primary = (count($datum['editions']) == 1) ?
                    true :
                    $editionData['is_primary'] ?? false;

                $edition->game_edition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->description = $editionData['description'];
                $edition->price = $editionData['price'] ?? null;
                $edition->quantity = $editionData['quantity'] ?? 1;
                $edition->weight = $editionData['weight'] ?? null;

                if (!empty($editionData['references'])) {
                    $this->setReferences($editionData['references'], $edition);
                }

                $edition->save();
                $item->editions->add($edition);
            }

            foreach ($datum['categories'] as $categoryData) {
                $category = Category::query()->where('slug', $categoryData)->firstOrFail();
                $item->categories->add($category);
            }
        }
    }
}
