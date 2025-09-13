<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonCallType;
use App\Enums\JsonRenderMode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use BadMethodCallException;

abstract class AbstractModel extends Model implements Arrayable, ModelInterface
{
    /**
     * @var array{
     *     int: { string: string },
     *     int: { string, string }
     * }
     */
    protected array $schema = [
        JsonRenderMode::SHORT->value => [],
        JsonRenderMode::FULL->value => [],
    ];

    public function render(JsonKeyPair $keyPair, JsonRenderMode $mode): string|bool|int|array|Attribute|null
    {
        $cleanKey = $keyPair->getCleanKey();
        $cleanType = $keyPair->getCleanType();
        $camelMethod = $keyPair->getCleanKeyAsCamelCase();

        return match ($keyPair->getCallType()) {
            JsonCallType::PROPERTY => $this->{$cleanKey},
            JsonCallType::IS_CLASS => $keyPair->isOptional() ?
                $this->{$camelMethod}?->toArray($mode) ?? null :
                $this->{$camelMethod}->toArray($mode),
            JsonCallType::COLLECTION => $this->{$cleanKey}()->collect()->toArray($mode),
            JsonCallType::METHOD => call_user_func_array([$this, $cleanType], []),
            JsonCallType::METHOD_ON_PROPERTY => $this->{$keyPair->getPropertyName()}->{$keyPair->getMethodName()}(),
            JsonCallType::PROPERTY_CHAIN => match (count($pieces = $keyPair->getPropertiesInChain())) {
                0 => throw new BadMethodCallException(),
                1 => $this->{$pieces[0]},
                2 => $this->{$pieces[0]}->{$pieces[1]},
                3 => $this->{$pieces[0]}->{$pieces[1]}->{$pieces[2]},
                4 => $this->{$pieces[0]}->{$pieces[1]}->{$pieces[2]}->{$pieces[3]},
            }
        };
    }

    public static function collection(array|Collection $input): ModelCollection
    {
        return ModelCollection::make($input);
    }

    public function toArray(
        JsonRenderMode $mode = JsonRenderMode::SHORT,
        array $exclude = []
    ): array {
        $short = [];

        foreach ($this->schema[JsonRenderMode::SHORT->value] as $key => $type) {
            $keyPair = new JsonKeyPair(key: $key, type: $type);
            $cleanKey = $keyPair->getCleanKey();
            $short[$cleanKey] = $this->render($keyPair, $mode);
        }

        if ($mode == JsonRenderMode::SHORT) {
            return $short;
        }

        $full = [];

        foreach ($this->schema[JsonRenderMode::FULL->value] as $key => $type) {
            $keyPair = new JsonKeyPair(key: $key, type: $type);
            $cleanKey = $keyPair->getCleanKey();
            $full[$cleanKey] = $this->render($keyPair, $mode);
        }

        return array_merge_recursive($short, $full);
    }
}
