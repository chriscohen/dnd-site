<?php

declare(strict_types=1);

namespace Tests\Data;

use Database\Seeders\AbstractYmlSeeder;

class ConcreteYmlSeeder extends AbstractYmlSeeder
{
    protected string $path = '';
    protected string $model = DummyModel::class;
}
