<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PrerequisiteType;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;
use App\Models\Prerequisites\Prerequisite;
use App\Models\Prerequisites\PrerequisiteValue;
use App\Models\Reference;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;

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
            $item->description = $datum['description'] ?? null;

            $item->save();

            foreach ($datum['editions'] as $editionData) {
                $edition = new FeatEdition();
                $edition->feat()->associate($item);
                $edition->id = $editionData['id'];
                $edition->description = $editionData['description'] ?? null;

                $edition->save();

                foreach ($editionData['references'] as $referenceData) {
                    $reference = new Reference();
                    $reference->entity()->associate($edition);
                    $source = Source::query()->where('slug', $referenceData['source'])->first();
                    $reference->edition()->associate($source->primaryEdition());
                    $reference->page_from = $referenceData['page_from'];
                    $reference->page_to = $referenceData['page_to'] ?? null;
                    $reference->save();
                }

                foreach ($editionData['prerequisites'] as $prerequisiteData) {
                    $prerequisite = new Prerequisite();
                    $prerequisite->featEdition()->associate($edition);
                    $prerequisite->type = PrerequisiteType::tryFromString($prerequisiteData['type']);

                    $prerequisite->save();

                    foreach ($prerequisiteData['values'] as $valueData) {
                        $value = new PrerequisiteValue();
                        $value->prerequisite()->associate($prerequisite);
                        $value->value = $valueData;
                        $value->save();
                    }
                }
            }
        }
    }
}
