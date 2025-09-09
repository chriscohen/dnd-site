<?php

namespace App\Http\Controllers;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractController implements ControllerInterface
{
    protected $entityType = AbstractModel::class;
    protected $order = 'ASC';
    protected $orderKey = '';

    public function get(Request $request, string $slug)
    {
        return response()->json($this->getQuery()->where('slug', $slug)->first());
    }

    public function getQuery(): Builder
    {
        $limit = config('api.MAX_ITEMS_PER_REQUEST', 100);
        return $this->entityType::query()
            ->orderBy($this->orderKey, $this->order)
            ->limit($limit);
    }

    public function index()
    {
        $items = $this->getQuery()->get();
        $output = [];

        foreach ($items as $item) {
            $output[] = $item->toArray();
        }

        return response()->json($output);
    }
}
