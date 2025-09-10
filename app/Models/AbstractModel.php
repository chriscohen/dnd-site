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
    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        throw new BadMethodCallException('toArray() method is not defined');
    }
}
