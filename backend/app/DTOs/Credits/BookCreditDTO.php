<?php

declare(strict_types=1);

namespace App\DTOs\Credits;

use App\DTOs\AbstractDTO;
use App\DTOs\People\PersonDTO;
use App\DTOs\Sources\SourceSummaryDTO;
use App\Models\ModelInterface;
use App\Models\People\BookCredit;

readonly class BookCreditDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public PersonDTO|string $person,
        public string $role,
        public SourceSummaryDTO|string $source
    ) {
    }

    /**
     * @param BookCredit $model
     */
    public static function fromModel(ModelInterface $model, bool $withPerson = true, bool $withSource = false): static
    {
        return new static(
            id: $model->id,
            person: $withPerson ? PersonDTO::fromModel($model->person) : $model->person->slug,
            role: $model->role,
            source: $withSource ?
                SourceSummaryDTO::fromModel($model->edition->source) :
                $model->edition->source->slug
        );
    }
}
