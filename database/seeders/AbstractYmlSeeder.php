<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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
     * Properties in this list will not be imported to the model from the JSON.
     *
     * @var string[] $excludedProperties
     */
    protected array $excludedProperties = [];

    /**
     * @return void
     * @throws FileNotFoundException
     */
    public function run(): void
    {
        $data = $this->getDataFromFile();

        // For each item in the JSON file...
        foreach ($data as $datum) {
            // Remove excluded properties from JSON.
            $output = $this->removeExcludedProperties($datum);

            // Equivalent to ModelClass::create([...$datum]);
            $model = forward_static_call([$this->model, 'create'], $output);

            $this->doExtras($model, $datum);
        }
    }

    public function getDataFromFile(): array
    {
        if (Storage::disk('data')->missing($this->path)) {
            throw new FileNotFoundException('storage/data/' . $this->path . ' not found');
        }

        return json_decode(Storage::disk('data')->get($this->path), true);
    }

    public function doExtras(Model $model, array $datum): Model
    {
        return $model;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function removeExcludedProperties(array $input): array
    {
        $output = [];

        foreach ($input as $key => $value) {
            if (!in_array($key, $this->excludedProperties, true)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * @return string[]
     */
    public function getExcludedProperties(): array
    {
        return $this->excludedProperties;
    }

    /**
     * @param  string[]  $excludedProperties
     * @return $this
     */
    public function setExcludedProperties(array $excludedProperties): self
    {
        $this->excludedProperties = $excludedProperties;
        return $this;
    }
}
