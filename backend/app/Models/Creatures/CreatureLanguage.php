<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Models\AbstractModel;
use App\Models\Languages\Language;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property bool $can_hear
 * @property bool $can_read
 * @property bool $can_speak
 * @property bool $can_write
 * @property CreatureTypeEdition|CreatureSpeciesEdition $entity
 * @property Language $language
 */
class CreatureLanguage extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'can_hear' => 'boolean',
            'can_read' => 'boolean',
            'can_speak' => 'boolean',
            'can_write' => 'boolean',
        ];
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }

    public static function from5eJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();

        try {
            $languageSlug = str_replace(' ', '-', mb_strtolower($value['language']));
            $language = Language::query()->where('slug', $languageSlug)->firstOrFail();
            $item->language()->associate($language);
        } catch (ModelNotFoundException $e) {
            print "[WARNING] Could not find language: {$value['language']}\n";
            return $item;
        }

        $item->entity()->associate($parent);

        $item->save();
        return $item;
    }
}
