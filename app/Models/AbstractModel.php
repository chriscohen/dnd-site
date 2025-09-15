<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use BadMethodCallException;
use Illuminate\Support\Js;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    /**
     * A list of class names that will be excluded from nested JSON rendering to prevent infinite recursion.
     *
     * @var string[]
     */
    protected array $excluded = [];

    protected JsonRenderMode $renderMode = JsonRenderMode::SHORT;

    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function isExcluded(string $className): bool
    {
        return in_array($className, $this->excluded);
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT, array $exclude = []): array
    {
        $this->renderMode = $mode;
        $this->excluded = array_merge_recursive($this->excluded, $exclude);

        // If we reach here and this class is already excluded, don't render it to an array, to prevent infinite
        // recursion.
        if ($this->isExcluded(static::class)) {
            return [
                'recursion' => '*',
            ];
        }

        $this->excluded[] = static::class;

        $output = $this->toArrayShort();

        if ($mode == JsonRenderMode::SHORT) {
            return $output;
        }

        return array_merge_recursive($output, $this->toArrayLong());
    }
}
