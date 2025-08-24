<?php

namespace App\Models\Items;

use App\CommonMark\InternalLinkGenerator;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\Source;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property Source $source
 * @property Uuid $source_id
 * @property float $weight
 */
class ItemEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
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

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }
}
