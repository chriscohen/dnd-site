<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

use App\DTOs\Spells\SpellFullDTO;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\GetSpellsRequest;
use App\Models\Spells\Spell;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

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
        try {
            $id = Uuid::fromString($slug);
            $item = $this->query->where('id', $id)->first();
        } catch (InvalidArgumentException $e) {
            $item = $this->query->where('slug', $slug)->first();
        }

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
