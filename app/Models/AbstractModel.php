<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use BadMethodCallException;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    /**
     * @var array{
     *     int: { string: string },
     *     int: { string, string }
     * }
     */
    protected array $schema = [
        JsonRenderMode::SHORT->value => [],
        JsonRenderMode::FULL->value => [],
    ];

    public function cleanKey(string $key): string
    {
        return str_replace(['?', '[]', '()'], ['', '', ''], $key);
    }

    public function keyIsArray(string $key): bool
    {
        return str_ends_with($key, '[]');
    }

    public function keyIsOptional(string $key): bool
    {
        return str_starts_with($key, '?');
    }

    public function render(string $key, string $type): string|int|array|null
    {
        $message = '';

        // Key is mandatory...
        if (!$this->keyIsOptional($key)) {
            $cleanKey = $this->cleanKey($key);

            // Are we looking for a local method? It must exist.
            if ($this->typeIsMethod($type) && !method_exists(get_class($this), $this->cleanKey($type))) {
                $message = 'method ' . $type . ' does not exist on class ' . get_class($this);
            } elseif (!$this->typeIsMethod($type) && !isset($this->{$cleanKey})) {
                // We can't find the local property, but it's mandatory.
                $message = 'property ' . $cleanKey . ' does not exist on class ' . get_class($this);
            }
        }

        if (!empty($message)) {
            throw new BadMethodCallException('Key ' . $key . ' is not optional but ' . $message);
        }

        // If it's a method, call it.
        if ($this->typeIsMethod($type)) {
            return call_user_func_array([$this, $this->cleanKey($type)], []);
        } elseif (!$this->typeIsClass($type)) {
            // If it's a scalar value we can just return it here.
            return $this->{$key};
        } elseif (!$this->keyIsArray($key) && !$this->typeIsMethod($type)) {
            // If it's a single class, we can call toArray() on it.
            return $this->{$key}->toArray();
        } else {
            // If it's multiple, then we need to collect them.
            return $this->{$key}()->collect()->toArray();
        }
    }

    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function toArray(
        JsonRenderMode $mode = JsonRenderMode::SHORT,
        array $exclude = []
    ): array {
        $short = [];

        foreach ($this->schema[JsonRenderMode::SHORT->value] as $key => $value) {
            $cleanKey = $this->cleanKey($key);
            $short[$cleanKey] = $this->render($cleanKey, $value);
        }

        if ($mode == JsonRenderMode::SHORT) {
            return $short;
        }

        $full = [];

        foreach ($this->schema[JsonRenderMode::FULL->value] as $key => $value) {
            $cleanKey = $this->cleanKey($key);
            $full[$cleanKey] = $this->render($cleanKey, $value);
        }

        return array_merge_recursive($short, $full);
    }

    public function typeIsClass(string $type): bool
    {
        return str_contains($type, '\\');
    }

    public function typeIsMethod(string $type): bool
    {
        return str_ends_with($type, '()');
    }
}
