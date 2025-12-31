<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\Credits\BookCreditDTO;
use App\DTOs\People\PersonDTO;
use App\Models\People\BookCredit;
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

    public function credits(Request $request, string $slug): JsonResponse
    {
        $person = $this->query->where('slug', $slug)->first();

        if (empty($person)) {
            return response()->json([], 404);
        }

        $items = BookCredit::query()
            ->where('person_id', $person->id)
            ->with('edition')
            ->paginate(20)
            ->through(fn(BookCredit $credit) => BookCreditDTO::fromModel($credit, withPerson: false, withSource: true));

        return  response()->json(
            $items
        );
    }
}
