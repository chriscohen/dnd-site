<?php

declare(strict_types=1);

namespace App\Http\Controllers\Items;

use App\Http\Controllers\AbstractController;
use App\Models\Items\ItemType;

class ItemController extends AbstractController
{
    protected string $entityType = ItemType::class;
    protected string $orderKey = 'id';
}
