<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

use App\DTOs\Spells\SpellFullDTO;
use App\DTOs\Spells\SpellSummaryDTO;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\GetSpellsRequest;
use App\Models\Spells\Spell;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpellController extends AbstractController
{
    protected string $entityType = Spell::class;
    protected string $orderKey = 'name';

    public function editionQuery(string $editions): self
    {
        $parameters = $this->getEditionsFromQueryString($editions);

        $this->query->whereHas('editions', function ($query) use ($parameters) {
            $query->where('is_default', true);
            $query->whereIn('game_edition', $parameters);
        });

        return $this;
    }

    public function get(Request $request, string $slug): JsonResponse
    {
        $item = $this->query->where('slug', $slug)->first();

        if (empty($item)) {
            return response()->json([], 404);
        }

        return response()->json(SpellFullDTO::fromModel($item));
    }

    public function list(GetSpellsRequest $request): JsonResponse
    {
        $items = $this->getQuery()
            ->orderBy($this->orderKey)
            ->paginate(50)
            ->through(fn (Spell $item) => SpellFullDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
