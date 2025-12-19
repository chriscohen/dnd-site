<?php

declare(strict_types=1);

namespace App\Models\Languages;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?LanguageGroup $group
 * @property bool $is_exotic
 * @property bool $is_srd
 * @property ?string $origin
 * @property Collection<Reference> $references
 * @property ?LanguageScript $script
 *
 */
class Language extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'is_exotic' => 'boolean',
        'is_srd' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(LanguageGroup::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function script(): BelongsTo
    {
        return $this->belongsTo(LanguageScript::class);
    }

    public function toArrayFull(): array
    {
        return [
            'references' => ModelCollection::make($this->references)->toArray(),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'isExotic' => $this->is_exotic,
            'scriptName' => $this->script_name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        // Make sure the language doesn't already exist.
        $existing = static::query()->where('name', $value['name'])->first();

        if (empty($existing)) {
            $item = new static();

            if (!empty($parent)) {
                $item->group()->associate($parent);
            }

            $item->id = $value['id'] ?? Uuid::uuid4();
            $item->name = $value['name'];
            $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
            $item->is_exotic = $value['is_exotic'] ?? false;
            $item->is_srd = !empty($value['srd']) || !empty($value['src52']);

            if (!empty($values['script'])) {
                $script = LanguageScript::fromInternalJson($value['script']);
                $item->script()->associate($script);
            }

            $item->save();
        } else {
            $item = $existing;
        }

        $item->save();

        // References. We still create references for existing languages because the second time the same language
        // appears in the 5e.tools JSON, it will have a different source.
        static::create5eToolsReference($value, $item);

        return $item;
    }

    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
