<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;

interface ModelInterface
{
    public function cleanKey(string $key): string;

    public function keyIsArray(string $key): bool;

    public function keyIsOptional(string $key): bool;

    public function render(string $key, string $type): string|int|array|null;

    /**
     * @param  JsonRenderMode  $mode The render mode to use, whether to only return basic fields, or all of them.
     * @param  array  $exclude
     *   When nesting, this will cause some fields to be excluded. For example, if SpellEdition has Spell nested inside
     *   it, we don't want Spell to ALSO have SpellEdition inside, otherwise we get infinite recursion.
     * @return array
     */
    public function toArray(
        JsonRenderMode $mode = JsonRenderMode::SHORT,
        array $exclude = []
    ): array;

    public function typeIsClass(string $type): bool;

    public function typeIsMethod(string $type): bool;
}
