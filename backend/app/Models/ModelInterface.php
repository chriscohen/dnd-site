<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Faker\Generator as Faker;

interface ModelInterface
{
    /**
     * Create a new instance from an internal JSON representation, as an array.
     *
     * @param  array|string|int  $value
     * @param  ModelInterface|null  $parent
     * @return static
     */
    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static;

    /**
     * Import from the 5e.tools JSON format.
     * @param  array|string|int  $value  The JSON from 5e.tools, as an array.
     * @param ModelInterface|null $parent  The parent model, if any.
     */
    public static function from5eJson(array|string|int $value, ModelInterface $parent = null): static;

    /**
     * Create a new instance, with random values.
     */
    public static function generate(ModelInterface $parent = null): static;

    /**
     * Get the human-readable name of this thing.
     *
     * @param  ModelInterface|null  $parent
     * @return ModelInterface|null
     */

    /**
     * Static method for importing data that is additional to 5e.tools JSON.
     */
    public static function fromFeJsonExtra(array|string $value, ModelInterface $parent = null): ?static;

    /**
     * Non-static method for importing extra data from JSON.
     */
    public function fromExtraData(array|string $value, ?ModelInterface $parent = null): ?static;

    /**
     * Get a faker instance for this model.
     */
    public static function getFaker(): Faker;

    public function getName(): string;

    /**
     * The slug of this thing, which might be the parent's slug.
     *
     * For example, SpellEdition entities don't have their own slug, but the parent Spell does.
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * @param  JsonRenderMode  $mode The render mode to use, whether to only return basic fields, or all of them.
     * @param  array  $exclude
     *   When nesting, this will cause some fields to be excluded. For example, if SpellEdition has Spell nested inside
     *   it, we don't want Spell to ALSO have SpellEdition inside, otherwise we get infinite recursion.
     * @return array
     */
    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array;
}
