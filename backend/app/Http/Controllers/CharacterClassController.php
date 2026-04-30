<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CharacterClasses\CharacterClassSummaryDTO;
use App\Models\CharacterClasses\CharacterClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterClassController extends AbstractController
{
    protected string $entityType = CharacterClass::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        return response()->json([]);
    }

    public function index(Request $request): JsonResponse
    {
        $items = $this->query
            ->with([
                'editions'
            ])
            ->orderBy($this->orderKey)
            ->paginate(50)
            ->through(fn (CharacterClass $item) => CharacterClassSummaryDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
