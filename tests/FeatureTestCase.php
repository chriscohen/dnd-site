<?php

namespace Tests;

use App\Models\ModelInterface;
use Faker\Generator;
use Orchestra\Testbench\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = app(Generator::class);
    }

    protected function defineEnvironment($app): void
    {
        $this->defineEnvironmentMemory($app);
    }

    protected function defineEnvironmentMemory($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineEnvironmentFile($app): void
    {
        $dbPath = __DIR__ . '/testing.sqlite';

        if (!file_exists($dbPath)) {
            touch($dbPath);
        }

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => $dbPath,
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
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
