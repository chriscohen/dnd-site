<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sources;

use App\DTOs\People\BookCreditDTO;
use App\DTOs\Sources\SourceContentsDTO;
use App\DTOs\Sources\SourceFullDTO;
use App\DTOs\Sources\SourceSummaryDTO;
use App\Http\Controllers\AbstractController;
use App\Models\People\BookCredit;
use App\Models\Sources\Source;
use App\Models\Sources\SourceContents;
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
                'editions.credits',
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

    public function contents(Request $request, string $slug): JsonResponse
    {
        /** @var Source|null $item */
        $item = $this->query->where('slug', $slug)
            ->first();

        return empty($item->primaryEdition()) ?
            response()->json([], 404) :
            response()->json($item->primaryEdition()->contents->map(
                fn (SourceContents $contents) => SourceContentsDTO::fromModel($contents)
            ));
    }

    public function credits(Request $request, string $slug): JsonResponse
    {
        /** @var Source $item */
        $item = $this->query->where('slug', $slug)->first();

        $people = $item->primaryEdition()?->credits
            ->map(fn (BookCredit $credit) => BookCreditDTO::fromModel($credit)) ?? [];
        return response()->json($people);
    }
}
