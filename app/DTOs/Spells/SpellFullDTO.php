<?php

declare(strict_types=1);

namespace App\DTOs\Spells;

use App\DTOs\Media\MediaSummaryDTO;
use App\Models\Spells\SpellEdition;
use Illuminate\Support\Collection;

readonly class SpellFullDTO extends SpellSummaryDTO
{
    public function __construct(
        string $id,
        ?MediaSummaryDTO $image = null,
        string $name,
        string $slug,
        // Summary.
        /** @var Collection<SpellEditionFullDTO> $editions */
        public Collection $editions
    ) {
        parent::__construct($id, $image, $name, $slug);
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            image: !empty($model->image) ? MediaSummaryDTO::fromModel($model->image) : null,
            name: $model->name,
            slug: $model->slug,
            // Summary.
            editions: $model->editions->map(fn (SpellEdition $edition) => SpellEditionFullDTO::fromModel($edition))
        );
    }
}
