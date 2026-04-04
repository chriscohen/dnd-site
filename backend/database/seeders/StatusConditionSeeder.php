<?php

namespace Database\Seeders;

use App\Models\Conditions\Condition;

class StatusConditionSeeder extends AbstractYmlSeeder
{
    protected string $path = 'status-conditions.json';
    protected string $model = Condition::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            Condition::fromInternalJson($datum);
        }
    }
}
