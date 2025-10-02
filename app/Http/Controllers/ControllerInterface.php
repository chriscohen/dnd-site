<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;

interface ControllerInterface
{
    /**
     * Add a query for game editions to the query builder.
     */
    public function editionQuery(string $editions): self;

    /**
     * @param string $queryString
     *   The querystring parameter "editions=..."
     * @return string[]
     *   The editions from the query string, as an array.
     */
    public function getEditionsFromQueryString(string $queryString): array;

    public function getQuery(): Builder;
}
