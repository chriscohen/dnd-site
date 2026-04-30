<?php

declare(strict_types=1);

namespace App\DTOs\People;

use App\DTOs\AbstractDTO;

readonly class PersonDTO extends AbstractDTO
{
    public function __construct(
        public string $id,
        public string $slug,
        public ?string $artstation = null,
        public ?string $firstName = null,
        public ?array $initials = null,
        public ?string $instagram = null,
        public ?string $lastName = null,
        public ?string $middleNames = null,
        public ?string $twitter = null,
        public ?string $youtube = null
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            slug: $model->slug,
            artstation: $model->artstation,
            firstName: $model->first_name,
            initials: $model->initials ? str_split($model->initials) : null,
            instagram: $model->instagram,
            lastName: $model->last_name,
            middleNames: $model->middle_names,
            twitter: $model->twitter,
            youtube: $model->youtube
        );
    }
}
