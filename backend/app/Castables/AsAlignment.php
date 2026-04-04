<?php

declare(strict_types=1);

namespace App\Castables;

use App\Enums\Alignment\AlignmentGoodEvil;
use App\Enums\Alignment\AlignmentLawChaos;
use App\Models\Alignment\Alignment;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AsAlignment implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?Alignment
    {
        if (is_null($value)) {
            return null;
        } elseif (mb_strtolower($value) === 'n') {
            return new Alignment(AlignmentLawChaos::NEUTRAL, AlignmentGoodEvil::NEUTRAL);
        } elseif (mb_strtolower($value) === 'u') {
            return new Alignment();
        } elseif (mb_strtolower($value) === 'a') {
            return new Alignment(AlignmentLawChaos::ANY, AlignmentGoodEvil::ANY);
        } else {
            $letters = str_split($value);
            return new Alignment(
                AlignmentLawChaos::tryFromString($letters[0]) ?? AlignmentLawChaos::NEUTRAL,
                AlignmentGoodEvil::tryFromString($letters[1]) ?? AlignmentGoodEvil::NEUTRAL
            );
        }
    }

    /**
     * @param Alignment $value
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        return is_null($value) ? $value : $value->toStringShort();
    }
}
