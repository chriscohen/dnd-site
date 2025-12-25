<?php

declare(strict_types=1);

namespace App\Models\Alignment;

use App\Enums\Alignment\AlignmentGoodEvil;
use App\Enums\Alignment\AlignmentLawChaos;

class Alignment
{
    public function __construct(
        protected ?AlignmentLawChaos $lawChaos = null,
        protected ?AlignmentGoodEvil $goodEvil = null,
    ) {
    }

    public function isUnaligned(): bool
    {
        return $this->lawChaos === null && $this->goodEvil === null;
    }

    public function toString(): string
    {
        if ($this->isUnaligned()) {
            return 'Unaligned';
        }

        return $this->lawChaos === AlignmentLawChaos::NEUTRAL && $this->goodEvil === AlignmentGoodEvil::NEUTRAL ?
            'neutral' :
            $this->lawChaos->toString() . ' ' . $this->goodEvil->toString();
    }

    public function toStringShort(): string
    {
        if ($this->isUnaligned()) {
            return 'U';
        }

        return $this->lawChaos === AlignmentLawChaos::NEUTRAL && $this->goodEvil === AlignmentGoodEvil::NEUTRAL ?
            'N' :
            mb_strtoupper($this->lawChaos->toStringShort() . $this->goodEvil->toStringShort());
    }
}
