<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Text\TextEntry;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property Collection<TextEntry> $entries
 */
trait WithTextEntries
{
    public function entries(): MorphMany
    {
        return $this->morphMany(TextEntry::class, 'parent');
    }
}
