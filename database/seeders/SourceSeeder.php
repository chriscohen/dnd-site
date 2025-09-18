<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Enums\PublicationType;
use App\Enums\SourcebookType;
use App\Enums\SourceFormat;
use App\Enums\SourceType;
use App\Models\CampaignSetting;
use App\Models\Company;
use App\Models\Media;
use App\Models\ProductId;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use App\Models\Sources\SourceEditionFormat;
use App\Models\Sources\SourceSourcebookType;
use Carbon\Carbon;

class SourceSeeder extends AbstractYmlSeeder
{
    protected string $path = 'sources.json';
    protected string $model = Source::class;
    protected array $excludedProperties = [
        'cover_image'
    ];

    protected array $dependsOn = [
        CampaignSettingSeeder::class,
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $source = new Source();
            $source->id = $datum['id'];
            // If no slug, assume we can just urlencode the name.
            $source->slug = $datum['slug'] ?? self::makeSlug($datum['name']);
            $source->name = $datum['name'];

            if (!empty($datum['campaign_setting'])) {
                $setting = CampaignSetting::query()->where('slug', $datum['campaign_setting'])->firstOrFail();
                $source->campaignSetting()->associate($setting);
            }
            if (!empty($datum['cover_image'])) {
                $media = Media::createFromExisting([
                    'filename' => '/books/' . $datum['cover_image'],
                    'disk' => 's3',
                    'collection_name' => 'cover-images',
                ]);
                $source->coverImage()->associate($media);
            }
            $source->description = $datum['description'] ?? null;
            $source->game_edition = GameEdition::tryFromString($datum['game_edition']);
            $source->product_code = $datum['product_code'] ?? null;

            $source->publication_type = PublicationType::tryFromString($datum['publication_type']);
            $source->publisher_id = $datum['publisher_id'] ?? null;
            $source->source_type = SourceType::tryFromString($datum['source_type']);

            $source->save();

            foreach ($datum['product_ids'] ?? [] as $key => $value) {
                $company = Company::query()->where('slug', $key)->firstOrFail();
                $productId = new ProductId();
                $productId->origin()->associate($company);
                $productId->product_id = $value;
                $productId->source()->associate($source);
                $productId->save();
            }

            foreach ($datum['sourcebook_types'] ?? [] as $type) {
                $sourcebookType = SourcebookType::tryFromString($type);
                $model = new SourceSourcebookType();
                $model->source()->associate($source);
                $model->sourcebook_type = $sourcebookType;
                $model->save();
            }

            foreach ($datum['editions'] as $editionData) {
                $edition = new SourceEdition();
                $edition->id = $editionData['id'];
                $edition->source_id = $datum['id'];
                $edition->name = $editionData['name'];

                $edition->is_primary = (count($datum['editions']) == 1) ?
                    true :
                    ($editionData['is_primary'] ?? false);

                $edition->binding = Binding::tryFromString($editionData['binding']);
                $edition->isbn10 = $editionData['isbn10'] ?? null;
                $edition->isbn13 = $editionData['isbn13'] ?? null;
                $edition->pages = $editionData['pages'] ?? null;
                $edition->release_date = new Carbon($editionData['release_date']) ?? null;
                $edition->release_date_month_only = $editionData['release_date_month_only'] ?? false;

                $edition->save();

                foreach ($editionData['formats'] ?? [] as $formatData) {
                    $formatEnum = SourceFormat::tryFromString($formatData);
                    $format = new SourceEditionFormat();
                    $format->format = $formatEnum;
                    $format->edition()->associate($edition);
                    $format->save();
                }

                $source->editions->add($edition);
            }
        }
    }
}
