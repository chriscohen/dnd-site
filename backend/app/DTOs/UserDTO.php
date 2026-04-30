<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\ModelInterface;
use App\Models\User;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
    ) {
    }

    public static function fromModel(User $model): static
    {
        return new static(
            id: $model->id,
            email: $model->email
        );
    }
}
