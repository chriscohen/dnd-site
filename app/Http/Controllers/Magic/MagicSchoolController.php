<?php

declare(strict_types=1);

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\AbstractController;
use App\Models\Magic\MagicSchool;

class MagicSchoolController extends AbstractController
{
    protected $entityType = MagicSchool::class;
    protected $orderKey = 'id';
}
