<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ModelCollection implements Arrayable
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
            throw new InvalidArgumentException('Cannot add more than one type of model to ModelCollection');
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

    public function toArray(): array
    {
        $output = [];

        foreach ($this->items as $item) {
            $output[] = $item->toArray();
        }

        return $output;
    }
}
