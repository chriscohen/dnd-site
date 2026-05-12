<?php

declare(strict_types=1);

namespace App\Http\Requests;

class UpdateCampaignSettingRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logoId' => ['nullable', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'publisherId' => ['nullable', 'uuid'],
            'publicationType' => ['nullable', 'string', 'max:255'],
            'shortName' => ['nullable', 'string', 'max:255'],
            'startYear' => ['nullable', 'integer'],
        ];
    }
}
