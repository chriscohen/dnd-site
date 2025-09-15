<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CampaignSetting;

class CampaignSettingController extends AbstractController
{
    public string $entityType = CampaignSetting::class;
    public string $orderKey = 'name';
}
