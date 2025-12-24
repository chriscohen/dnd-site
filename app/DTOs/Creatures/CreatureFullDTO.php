<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\Models\Creatures\Creature;
use App\Models\Creatures\CreatureEdition;
use Illuminate\Support\Collection;

readonly class CreatureFullDTO extends CreatureSummaryDTO
{
    public function __construct(
        string $id,
        string $name,
        string $slug,
        /** @var Collection<CreatureEditionFullDTO> $editions */
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
                $model->editions->map(fn (CreatureEdition $item) => CreatureEditionFullDTO::fromModel($item)) :
                null,
            parent: $model->parent ? CreatureSummaryDTO::fromModel($model->parent) : null,
            children: $model->relationLoaded('children') ?
                $model->children->map(fn (Creature $item) => CreatureSummaryDTO::fromModel($item)) :
                null
        );
    }
}
