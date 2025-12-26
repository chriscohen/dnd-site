<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sources;

use App\DTOs\Sources\SourceFullDTO;
use App\DTOs\Sources\SourceSummaryDTO;
use App\Http\Controllers\AbstractController;
use App\Models\Sources\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends AbstractController
{
    protected string $entityType = Source::class;
    protected string $orderKey = 'name';

    public function editionQuery(string $editions): self
    {
        $parameters = $this->getEditionsFromQueryString($editions);

        $this->query->whereIn('game_edition', $parameters);
        return $this;
    }

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var Source|null $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'editions',
                'editions.contents',
                'editions.credits'
            ])
            ->first();

        return response()->json(
            $item === null ? [] : SourceFullDTO::fromModel($item)
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'campaign-setting' => 'string|nullable',
            'editions' => 'string|nullable',
            'publisher' => 'string|nullable',
        ]);

        if (!empty($request->input('editions'))) {
            $this->editionQuery($request->input('editions'));
        }

        if (!empty($request->input('campaign-setting'))) {
            $this->query->whereRelation('campaignSetting', 'slug', $request->input('campaign-setting'));
        }

        if (!empty($request->input('publisher'))) {
            $this->query->whereRelation('publisher', 'slug', $request->input('publisher'));
        }

        $this->query->orderBy($this->orderKey);

        $items = empty($request->get('includeChildren')) ?
            $this->query->whereNull('parent_id')->paginate(50) :
            $this->query->paginate(20);

        $items = $items->through(fn (Source $item) => SourceSummaryDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
