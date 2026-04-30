<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureMainType;
use App\Models\Creatures\CreatureMainTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Support\Collection;

readonly class CreatureMainTypeFullDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        /** @var Collection<CreatureMainTypeEditionDTO> $editions */
        public Collection $editions,
        public string $name,
        public string $plural,
        public string $slug
    ) {
    }

    /**
     * @param CreatureMainType $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            editions: $model->relationLoaded('editions') ? $model->editions->map(
                fn (CreatureMainTypeEdition $item) => CreatureMainTypeEditionDTO::fromModel($item)
            ) : collect(),
            name: $model->name,
            plural: $model->plural,
            slug: $model->slug
        );
    }
}
