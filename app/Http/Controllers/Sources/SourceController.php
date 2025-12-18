<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sources;

use App\Http\Controllers\AbstractController;
use App\Models\CampaignSetting;
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

    public function index(Request $request): JsonResponse
    {
        $this->preValidate($request);

        if (!empty($request->get('editions'))) {
            $this->editionQuery($request->get('editions'));
        }

        if (!empty($request->input('campaignSetting'))) {
            $this->query->whereRelation('campaignSetting', 'slug', $request->input('campaignSetting'));
        }

        $this->query->orderBy($this->orderKey);

        $items = empty($request->get('includeChildren')) ?
            $this->query->whereNull('parent_id')->get() :
            $this->query->get();
        $output = [];

        foreach ($items as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output);
    }
}
