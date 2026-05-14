<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

use App\DTOs\Spells\SpellEditionFullDTO;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\GetSpellsRequest;
use App\Models\Spells\SpellEdition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpellEditionController extends AbstractController
{
    protected string $entityType = SpellEdition::class;
    protected string $orderKey = 'game_edition';

    public function get(Request $request, string $id): JsonResponse
    {
        $item = $this->query->where('id', $id)->first();

        if (empty($item)) {
            return response()->json([], 404);
        }

        return response()->json(SpellEditionFullDTO::fromModel($item));
    }

    public function list(GetSpellsRequest $request): JsonResponse
    {
        $items = $this->getQuery()
            ->paginate(50)
            ->through(fn (SpellEdition $item) => SpellEditionFullDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
