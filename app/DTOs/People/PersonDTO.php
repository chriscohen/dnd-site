<?php

declare(strict_types=1);

namespace App\DTOs\People;

use App\DTOs\AbstractDTO;

readonly class PersonDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $slug,
        public readonly ?string $firstName = null,
        public readonly ?array $initials = null,
        public readonly ?string $instagram = null,
        public readonly ?string $lastName = null,
        public readonly ?string $twitter = null,
        public readonly ?string $youtube = null
    ) {
    }

    public static function fromModel(object $model): static
    {
        return new static(
            id: $model->id,
            slug: $model->slug,
            firstName: $model->first_name,
            initials: $model->initials ? str_split($model->initials) : null,
            instagram: $model->instagram,
            lastName: $model->last_name,
            twitter: $model->twitter,
            youtube: $model->youtube
        );
    }
}
