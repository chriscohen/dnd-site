<?php

namespace App\Models\Items;

use App\CommonMark\InternalLinkGenerator;
use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\Reference;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use League\CommonMark\Extension\Mention\MentionExtension;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property string $description
 * @property GameEdition $game_edition
 * @property bool $is_primary
 * @property Item $item
 * @property Uuid $item_id
 * @property int $price
 * @property int $quantity
 * @property Collection<Reference> $references
 * @property Collection<SpellEdition> $spells
 * @property float $weight
 */
class ItemEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'is_primary' => 'boolean',
    ];

    public function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->addExtension(new MentionExtension())
                ->commonmarkOptions([
                    'mentions' => [
                        'internal_link' => [
                            'prefix' => '@',
                            'pattern' => '([a-zA-Z0-9]+):([a-z-]+):([\w\'& ]+)',
                            'generator' => new InternalLinkGenerator(),
                        ]
                    ]
                ])
                ->toHtml($value),
        );
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function spells(): BelongsToMany
    {
        return $this->belongsToMany(SpellEdition::class, 'spell_material_components');
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'game_edition' => $this->game_edition?->toStringShort() ?? null,
            'is_primary' => $this->is_primary,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'references' => $this->references->collect()->toArray(),
            'weight' => $this->weight,
        ];
    }
}
