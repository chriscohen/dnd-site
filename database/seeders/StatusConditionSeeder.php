<?php

namespace Database\Seeders;

use App\Models\StatusConditions\StatusCondition;

class StatusConditionSeeder extends AbstractYmlSeeder
{
    protected string $path = 'status-conditions.json';
    protected string $model = StatusCondition::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            StatusCondition::fromInternalJson($datum);
        }
    }
}
