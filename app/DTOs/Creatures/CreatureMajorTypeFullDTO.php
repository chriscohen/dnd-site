<?php

declare(strict_types=1);

namespace App\DTOs\Creatures;

use App\DTOs\AbstractDTO;
use App\Models\Creatures\CreatureMajorType;
use App\Models\Creatures\CreatureMajorTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Support\Collection;

readonly class CreatureMajorTypeFullDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        /** @var Collection<CreatureMajorTypeEditionDTO> $editions */
        public Collection $editions,
        public string $name,
        public string $plural,
        public string $slug
    ) {
    }

    /**
     * @param CreatureMajorType $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            editions: $model->relationLoaded('editions') ? $model->editions->map(
                fn (CreatureMajorTypeEdition $item) => CreatureMajorTypeEditionDTO::fromModel($item)
            ) : collect(),
            name: $model->name,
            plural: $model->plural,
            slug: $model->slug
        );
    }
}
