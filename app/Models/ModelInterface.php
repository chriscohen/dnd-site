<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;

interface ModelInterface
{
    /**
     * Get the human-readable name of this thing.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * @param  JsonRenderMode  $mode The render mode to use, whether to only return basic fields, or all of them.
     * @param  array  $exclude
     *   When nesting, this will cause some fields to be excluded. For example, if SpellEdition has Spell nested inside
     *   it, we don't want Spell to ALSO have SpellEdition inside, otherwise we get infinite recursion.
     * @return array
     */
    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array;

    public function toArrayFull(): array;
    public function toArrayShort(): array;
    public function toArrayTeaser(): array;
}
