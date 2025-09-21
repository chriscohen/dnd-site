<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sources;

use App\Enums\GameEdition;
use App\Http\Controllers\AbstractController;
use App\Models\Sources\Source;
use App\Rules\ValidGameEdition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends AbstractController
{
    protected string $entityType = Source::class;
    protected string $orderKey = 'name';

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'edition' => ['string', new ValidGameEdition()]
        ]);

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

    public function editionQuery(string $editions): self
    {
        $parameters = $this->getEditionsFromQueryString($editions);

        $this->query->whereIn('game_edition', $parameters);
        return $this;
    }
}
