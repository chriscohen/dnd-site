<?php

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Company;
use App\Models\Media;
use App\Models\StatusConditions\StatusCondition;
use App\Models\StatusConditions\StatusConditionEdition;
use App\Models\StatusConditions\StatusConditionRule;

class StatusConditionSeeder extends AbstractYmlSeeder
{
    protected string $path = 'status-conditions.json';
    protected string $model = StatusCondition::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $item = new StatusCondition();
            $item->id = $datum['id'];
            $item->name = $datum['name'];
            $item->slug = $datum['slug'];

            $item->save();

            foreach ($datum['editions'] ?? [] as $editionData) {
                $edition = new StatusConditionEdition();
                $edition->statusCondition()->associate($item);
                $edition->gameEdition = GameEdition::tryFromString($editionData['game_edition']);
                $edition->description = $editionData['description'] ?? null;
                $edition->save();

                foreach ($editionData['rules'] ?? [] as $ruleData) {
                    $rule = new StatusConditionRule();
                    $rule->statusConditionEdition()->associate($edition);
                    $rule->rule = $ruleData;
                    $rule->save();
                }
            }
        }
    }
}
