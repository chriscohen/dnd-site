<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Feats\Feat;

class FeatController extends AbstractController
{
    public string $entityType = Feat::class;
}
