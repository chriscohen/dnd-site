<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    /**
     * A list of class names that will be excluded from nested JSON rendering to prevent infinite recursion.
     *
     * @var array{
     *     string: array
     * }
     */
    public static array $excluded = [];

    protected JsonRenderMode $renderMode = JsonRenderMode::SHORT;

    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function formatPrice(int $input): string
    {
        $output = [];

        foreach (['gp' => 10000, 'sp' => 100, 'cp' => 1] as $key => $multiplier) {
            $amount = intdiv($input, $multiplier);

            if ($amount > 0) {
                $output[] .= $amount . ' ' . $key;
                $input -= $amount;
            }
        }

        return implode(' ', $output);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function priceFromString(string $input): ?int
    {
        $amount = 0;

        foreach (['gp' => 10000, 'sp' => 100, 'cp' => 1] as $key => $multiplier) {
            preg_match('/([0-9]+)\s?' . $key . '/', $input, $matches);

            if (!empty($matches[1])) {
                $amount += ((int) $matches[1]) * $multiplier;
            }
        }

        return $amount === 0 ? null : $amount;
    }

    public function toArray(JsonRenderMode $mode = JsonRenderMode::SHORT): array
    {
        $this->renderMode = $mode;

        $output = $this->toArrayShort();

        if ($mode == JsonRenderMode::SHORT) {
            return $output;
        }

        $output = array_merge_recursive($output, $this->toArrayTeaser());

        if ($mode == JsonRenderMode::TEASER) {
            return $output;
        }

        return array_merge_recursive($output, $this->toArrayFull());
    }

    public static function makeSlug(string $input): string
    {
        return Str::slug($input);
    }

    public static function fromFeJson(array $value, ModelInterface $parent = null): ModelInterface
    {
        throw new \InvalidArgumentException('Not implemented');
    }
}
