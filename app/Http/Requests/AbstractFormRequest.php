<?php

namespace App\Http\Requests;

use App\Rules\ValidGameEdition;
use App\Rules\ValidMode;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'editions' => ['string', new ValidGameEdition()],
            'mode' => ['string', new ValidMode()],
        ];
    }
}
