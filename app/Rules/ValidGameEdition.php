<?php

namespace App\Rules;

use App\Enums\GameEdition;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidGameEdition implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $editions = explode(',', $value);

        foreach ($editions as $edition) {
            if (empty(GameEdition::tryFromString($value))) {
                $fail('"' . $edition . '" is not a valid game edition');
            };
        }
    }
}
