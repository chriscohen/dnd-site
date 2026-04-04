<?php

declare(strict_types=1);

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\AbstractController;
use App\Models\Magic\MagicDomain;

class MagicDomainController extends AbstractController
{
    protected string $entityType = MagicDomain::class;
    protected string $orderKey = 'id';
}
