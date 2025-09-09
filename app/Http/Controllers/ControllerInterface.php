<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;

interface ControllerInterface
{
    public function getQuery(): Builder;
}
