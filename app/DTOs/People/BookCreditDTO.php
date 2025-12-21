<?php

declare(strict_types=1);

namespace App\DTOs\People;

use App\DTOs\AbstractDTO;

readonly class BookCreditDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly PersonDTO $person,
        public readonly string $role
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            person: PersonDTO::fromModel($model->person),
            role: $model->role
        );
    }
}
