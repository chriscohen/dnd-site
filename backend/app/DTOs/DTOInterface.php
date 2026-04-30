<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\ModelInterface;

interface DTOInterface
{
    public static function fromModel(ModelInterface $model): static;
}
