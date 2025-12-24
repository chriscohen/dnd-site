<?php

declare(strict_types=1);

namespace App\DTOs\ArmorClass;

use App\DTOs\AbstractDTO;
use App\Models\ArmorClass\ArmorClassItem;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

readonly class ArmorClassDTO extends AbstractDTO
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Collection $items
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            items: $model->relationLoaded('items') ?
                $model->items->map(fn (ArmorClassItem $item) => ArmorClassItemDTO::fromModel($item)) :
                collect()
        );
    }
}
