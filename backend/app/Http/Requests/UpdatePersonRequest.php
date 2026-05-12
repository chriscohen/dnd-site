<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\People\Person;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        $personId = Person::query()
            ->where('slug', $this->route('slug'))->value('id');

        return [
            'slug' => ['required', 'string', 'max:255', Rule::unique('people')->ignore($personId)],
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['nullable', 'string', 'max:255'],
            'middleNames' => ['nullable', 'string', 'max:255'],
            'initials' => ['nullable', 'string', 'max:255'],
            'artstation' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
        ];
    }
}
