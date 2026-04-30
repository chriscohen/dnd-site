<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function self(Request $request): JsonResponse
    {
        return response()->json(empty($request->user()) ? [] : UserDTO::fromModel($request->user()));
    }
}
