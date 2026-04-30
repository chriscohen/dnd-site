<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends AbstractController
{
    protected string $entityType = Category::class;
    protected string $orderKey = 'id';
}
