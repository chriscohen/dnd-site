<?php

declare(strict_types=1);

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\AbstractController;
use App\Models\Magic\MagicSchool;

class MagicSchoolController extends AbstractController
{
    protected string $entityType = MagicSchool::class;
    protected string $orderKey = 'id';
    protected string $whereField = 'id';
    protected bool $hasEditions = false;
}
