<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends AbstractController
{
    protected $entityType = Category::class;
    protected $orderKey = 'id';
}
