<?php

declare(strict_types=1);

namespace App\Http\Controllers\Items;

use App\Http\Controllers\AbstractController;
use App\Models\Items\Item;

class ItemController extends AbstractController
{
    protected string $entityType = Item::class;
    protected string $orderKey = 'id';
}
