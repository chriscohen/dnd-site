<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Relations\Relation;
use Ramsey\Uuid\Uuid;

trait IdentifiesModel
{
    protected function identifyModel(string $type, int|string|Uuid $id): ?AbstractModel
    {
        /** @var AbstractModel $modelClass */
        $modelClass = collect(Relation::morphMap())->get($type);
        return $modelClass::query()->find($id);
    }

    protected function identifyType(string $type): ?string
    {
        /** @var string $modelClass */
        $modelClass = collect(Relation::morphMap())->get($type);
        return $modelClass;
    }
}
