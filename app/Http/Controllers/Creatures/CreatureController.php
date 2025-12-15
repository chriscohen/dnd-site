<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\Http\Controllers\AbstractController;
use App\Models\Creatures\Creature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatureController extends AbstractController
{
    protected string $entityType = Creature::class;
    protected string $orderKey = 'name';

    public function index(Request $request): JsonResponse
    {
        $this->preValidate($request);

        $this->query->orderBy($this->orderKey);

        $items = $this->query->get();

        foreach ($items as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output);
    }
}
