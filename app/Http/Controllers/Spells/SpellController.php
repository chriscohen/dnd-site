<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

use App\DTOs\Spells\SpellSummaryDTO;
use App\Http\Controllers\AbstractController;
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

    public function index(Request $request): JsonResponse
    {
        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate(50)
            ->through(fn (Spell $item) => SpellSummaryDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
