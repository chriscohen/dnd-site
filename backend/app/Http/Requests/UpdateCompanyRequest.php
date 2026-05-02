<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        $companyId = Company::where('slug', $this->route('slug'))->value('id');

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('companies')->ignore($companyId)],
            'short_name'  => ['nullable', 'string', 'max:255'],
            'website'     => ['nullable', 'url', 'max:255'],
            'product_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}
