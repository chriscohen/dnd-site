<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function self(Request $request): JsonResponse
    {
        $x = $request->user();
        return $request->user();
    }
}
