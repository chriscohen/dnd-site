<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spells;

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
        $this->preValidate($request);

        if (!empty($request->get('editions'))) {
            $this->editionQuery($request->get('editions'));
        }

        $result = $this->query->get();
        $output = [];

        foreach ($result as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output);
    }
}
