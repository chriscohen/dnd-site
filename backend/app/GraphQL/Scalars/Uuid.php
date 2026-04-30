<?php declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Language\AST\StringValueNode;
use Ramsey\Uuid\Uuid as RamseyUuid;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\Node;
use InvalidArgumentException;

class Uuid extends ScalarType
{
    public function serialize($value)
    {
        return $this->parseValue($value);
    }

    public function parseValue($value)
    {
        try {
            RamseyUuid::fromString($value);
        } catch (InvalidArgumentException $e) {
            throw new Error('Invalid UUID: ' . $e->getMessage());
        }
        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind);
        }

        try {
            RamseyUuid::fromString($valueNode->value);
        } catch (InvalidArgumentException $e) {
            throw new Error('Invalid UUID: ' . $e->getMessage());
        }

        return $valueNode->value;
    }
}
