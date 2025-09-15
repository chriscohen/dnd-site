<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Reference;

class ReferenceController extends AbstractController
{
    protected string $entityType = Reference::class;
    protected string $orderKey = 'id';
}
