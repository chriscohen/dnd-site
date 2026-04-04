<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ModelCollection
{
    protected ?string $itemClass = null;
    protected Collection $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(ModelInterface $model): ModelCollection
    {
        if ($this->isInitialised() && $this->itemClass !== $model::class) {
            //throw new InvalidArgumentException('Cannot add more than one type of model to ModelCollection');
        }
        $this->items[$model->id] = $model;
        $this->itemClass = $model::class;
        return $this;
    }

    /**
     * @param  ModelInterface[] | Collection<ModelInterface> $models
     * @return ModelCollection
     */
    public function addAll(array|Collection $models): ModelCollection
    {
        foreach ($models as $model) {
            $this->add($model);
        }
        return $this;
    }

    public function isInitialised(): bool
    {
        return $this->itemClass !== null;
    }

    public static function make(array|Collection $models): ModelCollection
    {
        $collection = new static();
        $collection->addAll($models);
        return $collection;
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $output = [];

        foreach ($this->items as $item) {
            $output[] = $item->toArray($mode);
        }

        return $output;
    }

    public function toString(): array
    {
        $output = [];

        foreach ($this->items as $item) {
            $output[] = $item->toString();
        }

        return $output;
    }

    public function toSearchResult(): array
    {
        $output = [];

        foreach ($this->items as $item) {
            $output[] = $item->toSearchResult();
        }

        return $output;
    }
}
