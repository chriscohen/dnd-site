<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CampaignSettingFullDTO;
use App\Enums\PublicationType;
use App\Http\Requests\UpdateCampaignSettingRequest;
use App\Models\CampaignSetting;
use App\Models\Company;
use App\Models\Media\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

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

    public function update(UpdateCampaignSettingRequest $request, string $slug): JsonResponse
    {
        /** @var CampaignSetting|null $campaignSetting */
        $campaignSetting = CampaignSetting::query()->where('slug', $slug)->first();

        if ($campaignSetting === null) {
            return response()->json(['message' => 'Campaign Setting not found'], 404);
        }

        $validated = $request->validated();
        $campaignSetting->slug = $validated['slug'];
        $campaignSetting->name = $validated['name'];

        if (!empty($validated['logoId'])) {
            $logo = Media::query()->where('id', $validated['logoId'])->first();
            if ($logo === null) {
                return response()->json(['message' => 'Logo not found'], 404);
            }
            $campaignSetting->logo()->associate($logo);
        }

        if (!empty($validated['publicationType'])) {
            try {
                $campaignSetting->publication_type = PublicationType::tryFromString($validated['publicationType']);
            } catch (InvalidArgumentException $e) {
                return response()->json(['message' => 'Invalid publication type'], 400);
            }
        }

        if (!empty($validated['publisher'])) {
            $company = Company::query()->where('slug', $validated['publisher'])->first();
            if ($company === null) {
                return response()->json(['message' => 'Publisher not found'], 404);
            }
            $campaignSetting->publisher()->associate($company);
        }

        if (!empty($validated['description'])) {
            $campaignSetting->description = $validated['description'];
        }
        if (!empty($validated['shortName'])) {
            $campaignSetting->short_name = $validated['shortName'];
        }
        if (!empty($validated['startYear'])) {
            $campaignSetting->start_year = $validated['startYear'];
        }

        $campaignSetting->save();
        $campaignSetting->load(['logo', 'publisher']);

        return response()->json(CampaignSettingFullDTO::fromModel($campaignSetting));
    }
}
