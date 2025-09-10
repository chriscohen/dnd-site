<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;

interface ModelInterface
{
    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array;
}
