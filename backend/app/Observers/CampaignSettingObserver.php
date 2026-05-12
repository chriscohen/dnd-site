<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\CampaignSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CampaignSettingObserver
{
    public function updated(CampaignSetting $campaignSetting): void
    {
        $contents = Storage::disk('data')->get('campaign-settings.json');
        $entries = json_decode($contents, true);

        $id = (string) $campaignSetting->id;
        $index = array_search($id, array_column($entries, 'id'));

        if ($index === false) {
            Log::error('Campaign Setting not found in companies.json; JSON not updated.', ['id' => $id]);
            return;
        }

        $existing = $entries[$index];

        $entry = [
            'id'   => $id,
            'slug' => $campaignSetting->slug,
            'name' => $campaignSetting->name,
        ];

        if (!empty($campaignSetting->short_name)) {
            $entry['shortName'] = $campaignSetting->short_name;
        }

        if (!empty($campaignSetting->publication_type)) {
            $entry['publicationType'] = $campaignSetting->publication_type;
        }

        // logo is not editable via the API — preserve whatever was in the JSON
        if (isset($existing['logo'])) {
            $entry['logo'] = $existing['logo'];
        }

        if (!empty($campaignSetting->start_year)) {
            $entry['startYear'] = $campaignSetting->start_year;
        }

        $entries[$index] = $entry;

        Storage::disk('data')->put(
            'campaign-settings.json',
            json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n"
        );
    }
}
