<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\People\BookCreditDTO;
use App\Enums\Sources\SourceFormat;
use App\Models\ModelInterface;
use App\Models\People\BookCredit;
use App\Models\Sources\SourceContents;
use App\Models\Sources\SourceEdition;
use App\Models\Sources\SourceEditionFormat;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class SourceEditionFullDTO extends SourceEditionSummaryDTO
{
    public function __construct(
        string $id,
        string $name,
        public ?string $binding = null,
        /** @var Collection<SourceContents> $contents */
        public ?Collection $contents = null,
        /** @var Collection<BookCredit> $credits */
        public ?Collection $credits = null,
        public ?array $formats = null,
        public bool $isPrimary = false,
        public ?string $isbn10 = null,
        public ?string $isbn13 = null,
        public ?int $pages = null,
        public ?Carbon $releaseDate = null
    ) {
        parent::__construct($id, $name);
    }

    /**
     * @param SourceEdition $model
     */
    public static function fromModel(ModelInterface $model): static
    {
        return new static(
            id: $model->id,
            name: $model->name,
            binding: $model->binding,
            contents: $model->relationLoaded('contents') ?
                $model->contents->map(fn (SourceContents $item) => SourceContentsDTO::fromModel($item)) :
                null,
            credits: $model->relationLoaded('credits') ?
                $model->credits->map(fn (BookCredit $item) => BookCreditDTO::fromModel($item)) :
                null,
            formats: $model->formats->map(fn (SourceEditionFormat $item) => $item->format)->toArray(),
            isPrimary: $model->is_primary,
            isbn10: $model->isbn10,
            isbn13: $model->isbn13,
            pages: $model->pages,
            releaseDate: $model->release_date
        );
    }
}
