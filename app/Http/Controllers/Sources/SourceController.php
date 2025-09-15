<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sources;

use App\Http\Controllers\AbstractController;
use App\Models\Source;

class SourceController extends AbstractController
{
    protected string $entityType = Source::class;
    protected string $orderKey = 'name';
}
