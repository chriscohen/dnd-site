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

    public function index(Request $request): JsonResponse
    {
        if (!empty($request->input('editions'))) {
            $this->editionQuery($request->input('editions'));
        }

        $this->query->orderBy($this->orderKey);

        $items = $this->query->paginate(20)->through(fn($item) => CampaignSettingFullDTO::fromModel($item));

        return response()->json($items);
    }
}
