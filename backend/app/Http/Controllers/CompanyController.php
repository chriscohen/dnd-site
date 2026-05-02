<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CompanyFullDTO;
use App\DTOs\CompanySummaryDTO;
use App\Http\Requests\UpdateCompanyRequest;
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
            ->orderBy($this->orderKey)
            ->first();

        return response()->json($item === null ? [] : CompanyFullDTO::fromModel($item));
    }

    public function index(Request $request): JsonResponse
    {
        $this->query->orderBy($this->orderKey);

        $items = $this->query
            ->paginate(50)
            ->through(fn(Company $item) => CompanySummaryDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }

    public function update(UpdateCompanyRequest $request, string $slug): JsonResponse
    {
        /** @var Company|null $company */
        $company = Company::where('slug', $slug)->first();

        if ($company === null) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $validated = $request->validated();
        $company->name = $validated['name'];
        $company->slug = $validated['slug'];
        $company->short_name = $validated['short_name'] ?? null;
        $company->website = $validated['website'] ?? null;
        $company->product_url = $validated['product_url'] ?? null;
        $company->save();

        $company->load(['logo', 'products']);

        return response()->json(CompanyFullDTO::fromModel($company));
    }
}
