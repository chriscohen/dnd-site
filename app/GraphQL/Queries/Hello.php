<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final class Hello
{
    public function __invoke(): string
    {
        return 'Hello world!';
    }
}
