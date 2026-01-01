<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\Models\Creatures\CreatureType;
use App\Models\Creatures\CreatureTypeEdition;
use Illuminate\Support\Collection;

readonly class CreatureTypeFullDTO extends CreatureSummaryDTO
{
    public function __construct(
        string $id,
        string $name,
        string $slug,
        /** @var Collection<CreatureTypeTypeEditionFullDTO> $editions */
        public readonly ?Collection $editions = null,
        public readonly ?CreatureSummaryDTO $parent = null,
        /** @var Collection<CreatureSummaryDTO> $children */
        public readonly ?Collection $children = null
    ) {
        parent::__construct($id, $name, $slug);
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            slug: $model->slug,
            editions: $model->relationLoaded('editions') ?
                $model->editions->map(
                    fn (CreatureTypeEdition $item) => CreatureTypeTypeEditionFullDTO::fromModel($item)
                ) :
                null,
            parent: $model->parent ? CreatureSummaryDTO::fromModel($model->parent) : null,
            children: $model->relationLoaded('children') ?
                $model->children->map(fn (CreatureType $item) => CreatureSummaryDTO::fromModel($item)) :
                null
        );
    }
}
