<?php

declare(strict_types=1);

namespace App\DTOs\Sources;

use App\DTOs\CompanySummaryDTO;
use App\DTOs\Credits\BookCreditDTO;
use App\DTOs\ProductIdDTO;
use App\Models\ModelInterface;
use App\Models\People\BookCredit;
use App\Models\ProductId;
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
        public bool $hasContents = false,
        public bool $hasCredits = false,
        public bool $isPrimary = false,
        public ?string $isbn10 = null,
        public ?string $isbn13 = null,
        public ?int $pages = null,
        public ?string $productCode = null,
        /** @var Collection<ProductIdDTO> */
        public Collection $productIds,
        public ?CompanySummaryDTO $publisher = null,
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
            hasContents: $model->relationLoaded('contents') && $model->hasContents,
            hasCredits: $model->relationLoaded('credits') && $model->hasCredits,
            isPrimary: $model->is_primary,
            isbn10: $model->isbn10,
            isbn13: $model->isbn13,
            pages: $model->pages,
            productCode: $model->product_code,
            productIds: $model->relationLoaded('productIds') ?
                $model->productIds->map(fn (ProductId $item) => $item) :
                collect(),
            publisher: !empty($model->publisher) ? CompanySummaryDTO::fromModel($model->publisher) : null,
            releaseDate: $model->release_date
        );
    }
}
