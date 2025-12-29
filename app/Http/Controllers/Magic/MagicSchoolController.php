<?php

declare(strict_types=1);

namespace App\Http\Controllers\Magic;

use App\DTOs\Magic\MagicSchoolFullDTO;
use App\Http\Controllers\AbstractController;
use App\Models\Magic\MagicSchool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MagicSchoolController extends AbstractController
{
    protected string $entityType = MagicSchool::class;
    protected string $orderKey = 'id';
    protected string $whereField = 'id';
    protected bool $hasEditions = false;

    public function get(Request $request, string $slug): JsonResponse
    {
        $item = $this->query
            ->where('slug', $slug)
            ->first();

        return empty($item) ?
            response()->json([], 404) :
            response()->json(MagicSchoolFullDTO::fromModel($item));
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'subschools' => 'boolean|nullable|default=false',
        ]);

        if (!$request->boolean('subschools')) {
            $this->query->whereNull('parent_id');
        }

        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate(50)
            ->through(fn (MagicSchool $item) => MagicSchoolFullDTO::fromModel($item, withChildren: true));

        return response()->json($items->withQueryString());
    }
}
