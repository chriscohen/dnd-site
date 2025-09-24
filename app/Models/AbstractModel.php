<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    /**
     * A list of class names that will be excluded from nested JSON rendering to prevent infinite recursion.
     *
     * @var array{
     *     string: array
     * }
     */
    public static array $excluded = [];

    protected JsonRenderMode $renderMode = JsonRenderMode::SHORT;

    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $this->renderMode = $mode;

        $output = $this->toArrayShort();

        if ($mode == JsonRenderMode::SHORT) {
            return $output;
        }

        $output = array_merge_recursive($output, $this->toArrayTeaser());

        if ($mode == JsonRenderMode::TEASER) {
            return $output;
        }

        return array_merge_recursive($output, $this->toArrayFull());
    }
}
