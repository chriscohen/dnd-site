<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyObserver
{
    public function updated(Company $company): void
    {
        $contents = Storage::disk('data')->get('companies.json');
        $entries = json_decode($contents, true);

        $id = (string) $company->id;
        $index = array_search($id, array_column($entries, 'id'));

        if ($index === false) {
            Log::error('Company not found in companies.json; JSON not updated.', ['id' => $id]);
            return;
        }

        $existing = $entries[$index];

        $entry = [
            'id'   => $id,
            'slug' => $company->slug,
            'name' => $company->name,
        ];

        // logo is not editable via the API — preserve whatever was in the JSON
        if (isset($existing['logo'])) {
            $entry['logo'] = $existing['logo'];
        }

        if (!empty($company->product_url)) {
            $entry['product_url'] = $company->product_url;
        }

        if (!empty($company->short_name)) {
            $entry['short_name'] = $company->short_name;
        }

        if (!empty($company->website)) {
            $entry['website'] = $company->website;
        }

        $entries[$index] = $entry;

        Storage::disk('data')->put(
            'companies.json',
            json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n"
        );
    }
}
