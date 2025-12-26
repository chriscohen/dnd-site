<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CompanyFullDTO;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends AbstractController
{
    protected string $entityType = Company::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var Company $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'products'
            ])
            ->first();

        return response()->json($item === null ? [] : CompanyFullDTO::fromModel($item));
    }

    public function index(Request $request): JsonResponse
    {

    }
}
