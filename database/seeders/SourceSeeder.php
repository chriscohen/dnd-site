<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Enums\PublicationType;
use App\Enums\SourceType;
use App\Models\Source;
use App\Models\SourceEdition;
use Carbon\Carbon;

class SourceSeeder extends AbstractYmlSeeder
{
    protected string $path = 'sources.json';
    protected string $model = Source::class;
    protected array $excludedProperties = [
        'cover_image'
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $source = new Source();
            $source->id = $datum['id'];
            // If no slug, assume we can just urlencode the name.
            $source->slug = $datum['slug'] ?? urlencode(mb_strtolower($datum['name']));
            $source->name = $datum['name'];
            $source->description = $datum['description'] ?? null;
            $source->product_code = $datum['product_code'] ?? null;
            $source->source_type = SourceType::tryFromString($datum['source_type']);
            $source->game_edition = GameEdition::tryFromString($datum['game_edition']);
            $source->publication_type = PublicationType::tryFromString($datum['publication_type']);
            $source->cover_image = $datum['cover_image'] ?? null;
            $source->publisher_id = $datum['publisher_id'] ?? null;
            $source->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new SourceEdition();
                $edition->id = $editionData['id'];
                $edition->source_id = $datum['id'];
                $edition->name = $editionData['name'];
                $edition->binding = Binding::tryFromString($editionData['binding']);
                $edition->isbn10 = $editionData['isbn10'] ?? null;
                $edition->isbn13 = $editionData['isbn13'] ?? null;
                $edition->pages = $editionData['pages'] ?? null;
                $edition->release_date = new Carbon($editionData['release_date']) ?? null;
                $edition->release_date_month_only = $editionData['release_date_month_only'] ?? false;

                $edition->save();
                $source->editions->add($edition);
            }
        }
    }
}
