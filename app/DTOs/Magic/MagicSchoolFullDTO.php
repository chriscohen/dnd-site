<?php

declare(strict_types=1);

namespace App\DTOs\Magic;

use App\DTOs\Media\MediaSummaryDTO;
use App\Models\Magic\MagicSchool;
use App\Models\ModelInterface;
use Illuminate\Support\Collection;

readonly class MagicSchoolFullDTO extends MagicSchoolSummaryDTO
{
    public function __construct(
        string $id,
        string $name,
        // Summary.
        /** @var Collection<MagicSchoolFullDTO> $children */
        public ?Collection $children = null,
        public ?string $description = null,
        public ?MediaSummaryDTO $image = null,
        public ?MagicSchoolSummaryDTO $parent = null,
        public ?string $shortName = null
    ) {
        parent::__construct($id, $name);
    }

    /**
     * @param MagicSchool $model
     */
    public static function fromModel(ModelInterface $model, bool $withChildren = false): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            // Summary.
            children: $withChildren ? $model->children->map(
                fn (MagicSchool $item) => MagicSchoolFullDTO::fromModel($item)
            ) : null,
            description: $model->description,
            image: !empty($model->image) ? MediaSummaryDTO::fromModel($model->image) : null,
            parent: $model->parent ? MagicSchoolSummaryDTO::fromModel($model->parent) : null,
            shortName: $model->short_name
        );
    }
}
