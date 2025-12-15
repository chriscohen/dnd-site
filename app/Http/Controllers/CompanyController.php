<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends AbstractController
{
    protected string $entityType = Company::class;
    protected string $orderKey = 'name';
}
