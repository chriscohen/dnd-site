<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Feats\Feature;

class FeatController extends AbstractController
{
    public string $entityType = Feature::class;
}
