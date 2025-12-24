<?php

namespace Tests;

use App\Models\ModelInterface;
use Faker\Generator;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = app(Generator::class);
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(realpath(__DIR__ . '../../database/migrations'));
    }

    protected function seed5eData(string $path, string $className): ModelInterface
    {
        $json = json_decode(file_get_contents($path), true);
        return $className::from5eJson($json);
    }
}
