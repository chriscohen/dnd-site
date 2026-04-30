<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CampaignSettingFullDTO;
use App\Models\CampaignSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignSettingController extends AbstractController
{
    public string $entityType = CampaignSetting::class;
    public string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var CampaignSetting|null $item */
        $item = $this->query->where('slug', $slug)->first();

        return empty($item) ?
            response()->json([], 404) :
            response()->json(CampaignSettingFullDTO::fromModel($item));
    }

    public function index(Request $request): JsonResponse
    {
        if (!empty($request->input('editions'))) {
            $this->editionQuery($request->input('editions'));
        }

        $this->query->orderBy($this->orderKey);

        $items = $this->query->paginate(50)->through(fn($item) => CampaignSettingFullDTO::fromModel($item));

        return response()->json($items);
    }
}
