<?php

namespace App\Http\Controllers;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Psy\Util\Json;

abstract class AbstractController implements ControllerInterface
{
    protected $entityType = AbstractModel::class;
    protected $order = 'ASC';
    protected $orderKey = '';

    public function get(Request $request, string $slug)
    {
        $model = $this->getQuery()->where('slug', $slug)->first();

        return response()->json($model->toArray($this->getMode($request)));
    }

    public function getMode(Request $request): JsonRenderMode
    {
        return JsonRenderMode::tryFromString($request->query('mode', 'short'));
    }

    public function getQuery(): Builder
    {
        $limit = config('api.MAX_ITEMS_PER_REQUEST', 100);
        return $this->entityType::query()
            ->orderBy($this->orderKey, $this->order)
            ->limit($limit);
    }

    public function index(Request $request)
    {
        $items = $this->getQuery()->get();
        $output = [];

        foreach ($items as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output);
    }
}
