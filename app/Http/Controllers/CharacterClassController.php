<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CharacterClasses\CharacterClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterClassController extends AbstractController
{
    protected string $entityType = CharacterClass::class;
    protected string $orderKey = 'name';

    public function index(Request $request): JsonResponse
    {
        $this->preValidate($request);

        $req
    }
}
