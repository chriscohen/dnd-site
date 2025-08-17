<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use BadMethodCallException;

abstract class AbstractYmlSeeder extends Seeder
{
    /**
     * The path to the JSON file that contains the data, relative to storage/data.
     *
     * @var string
     */
    protected string $path;

    /**
     * The class of the model that should be used for the data.
     *
     * @var string
     */
    protected string $model;

    /**
     * @var string[]
     */
    protected array $schema = [];

    /**
     * @return void
     * @throws FileNotFoundException
     */
    public function run(): void
    {
        // Make sure the file exists.
        if (Storage::disk('data')->missing($this->path)) {
            throw new FileNotFoundException('storage/data/' . $this->path . ' not found');
        }

        $data = json_decode(Storage::disk('data')->get($this->path), true);

        // For each item in the JSON file...
        foreach ($data as $datum) {
            // Equivalent to ModelClass::create([...$datum]);
            forward_static_call([$this->model, 'create'], $datum);
        }
    }
}
