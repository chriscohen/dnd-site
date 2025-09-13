<?php

namespace App\Models;

use App\Enums\JsonCallType;
use BadMethodCallException;

class JsonKeyPair
{
    protected string $cleanKey = '';
    protected string $cleanType = '';
    protected JsonCallType $callType;
    protected string $methodName = '';
    protected string $propertyName = '';

    public function __construct(
        protected string $key,
        protected string $type
    ) {
        $this->cleanKey = str_replace(['?', '[]'], ['', '', ''], $key);

        $this->cleanType = str_replace(['()'], [''], $this->type);

        if ($this->typeIsMethod()) {
            $this->callType = JsonCallType::METHOD;
        } elseif ($this->typeIsClass()) {
            $this->callType = JsonCallType::IS_CLASS;
        } elseif ($this->keyIsArray()) {
            $this->callType = JsonCallType::COLLECTION;
        } elseif ($this->typeIsMethodOnProperty()) {
            $this->callType = JsonCallType::METHOD_ON_PROPERTY;
            $pieces = $this->getPropertyAndMethod();
            $this->propertyName = $pieces[0];
            $this->methodName = $pieces[1];
        } elseif ($this->typeIsPropertyChain()) {
            $this->callType = JsonCallType::PROPERTY_CHAIN;
        } else {
            $this->callType = JsonCallType::PROPERTY;
        }
    }

    public function getCallType(): JsonCallType
    {
        return $this->callType;
    }

    public function getCleanKey(): string
    {
        return $this->cleanKey;
    }

    public function getCleanType(): string
    {
        return $this->cleanType;
    }

    public function getMethodName(): string
    {
        if (!$this->typeIsMethodOnProperty()) {
            throw new BadMethodCallException();
        }
        return $this->methodName;
    }

    /**
     * @return string[]
     */
    public function getPropertiesInChain(): array
    {
        return explode('->', $this->type);
    }

    public function getPropertyName(): string
    {
        if (!$this->typeIsMethodOnProperty()) {
            throw new BadMethodCallException();
        }
        return $this->propertyName;
    }

    /**
     * @return array{
     *     string,
     *     string
     * }
     */
    public function getPropertyAndMethod(): array
    {
        if (!$this->typeIsMethodOnProperty()) {
            throw new BadMethodCallException();
        }

        $type = str_replace('()', '', $this->type);
        return explode('->', $type);
    }

    public function getCleanKeyAsCamelCase(): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->cleanKey))));
    }

    public function keyIsArray(): bool
    {
        return str_ends_with($this->key, '[]');
    }

    public function isOptional(): bool
    {
        return str_starts_with($this->key, '?');
    }

    public function typeIsClass(): bool
    {
        return str_contains($this->type, '\\');
    }

    public function typeIsMethod(): bool
    {
        return !str_contains($this->type, '->') && str_ends_with($this->type, '()');
    }

    public function typeIsMethodOnProperty(): bool
    {
        return str_contains($this->type, '->') && str_ends_with($this->type, '()');
    }

    public function typeIsPropertyChain(): bool
    {
        return count(explode('->', $this->type)) > 2;
    }
}
