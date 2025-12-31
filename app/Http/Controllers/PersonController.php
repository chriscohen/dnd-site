<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\People\PersonDTO;
use App\Models\People\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonController extends AbstractController
{
    public string $entityType = Person::class;
    public string $orderKey = 'first_name';

    public function get(Request $request, string $slug): JsonResponse
    {
        $item = $this->query->where('slug', $slug)->first();

        return empty($item) ?
            response()->json([], 404) :
            response()->json(PersonDTO::fromModel($item));
    }

    public function index(Request $request): JsonResponse
    {
        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate(20)
            ->through(fn(Person $item) => PersonDTO::fromModel($item));

        return response()->json($items);
    }
}
