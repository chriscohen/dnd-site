<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\JsonRenderMode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mode = JsonRenderMode::tryFromString($value);

        if (empty($mode)) {
            $fail('"' . $value . '" is not a valid mode.');
        }
    }
}
