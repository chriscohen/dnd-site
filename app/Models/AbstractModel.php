<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function toArray(): array
    {
        return [];
    }
}
