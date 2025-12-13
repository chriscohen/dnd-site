<?php

declare(strict_types=1);

namespace App\Models\Text;

use App\Enums\TextEntryType;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<TextEntry> $children
 * @property ?string $entry_data
 * @property ?string $name
 * @property ModelInterface $parent
 * @property ?string $text
 * @property TextEntryType $type
 */
class TextEntry extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'text_entries';

    public $casts = [
        'type' => TextEntryType::class,
    ];

    public function children(): HasMany
    {
        return $this->hasMany(TextEntry::class, 'parent_id');
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = Uuid::uuid4();
        $item->parent()->associate($parent);

        // If the entry is just a text string, create and return it right away.
        if (is_string($value)) {
            $item->text = $value;
            $item->save();
            return $item;
        }

        $item->type = TextEntryType::tryFromString($value['type']);

        if (!empty($value['name'])) {
            $item->name = $value['name'];
        }
        if (!empty($value['caption'])) {
            $item->name = $value['caption'];
        }

        if ($item->type == TextEntryType::LIST) {
            $item->entry_data = json_encode($value['items']);
        } elseif ($item->type == TextEntryType::TABLE) {
            $item->entry_data = json_encode([
                'colLabels' => $value['colLabels'] ?? [],
                'colStyles' => $value['colStyles'] ?? [],
                'rows' => $value['rows'] ?? [],
            ]);
        }

        $item->save();

        foreach ($value['entries'] ?? [] as $childValue) {
            $child = static::fromInternalJson($childValue, $item);
            $item->children()->save($child);
        }

        $item->save();
        return $item;
    }

    public function toArrayFull(): array
    {
        $output = [
            'parent_id' => $this->parent->id,
            'text' => $this->text,
        ];
        if (!empty($this->entry_data)) {
            $output['entry_data'] = json_decode($this->entry_data, true);
        }
        $output['children'] = ModelCollection::make($this->children)->toArray($this->renderMode);
        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
