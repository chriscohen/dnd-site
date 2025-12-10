<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Sources\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productIds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Source::class, 'sourceId');
            $table->foreignIdFor(Company::class, 'originId');
            $table->string('productId');

            $table->unique(['originId', 'productId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productIds');
    }
};
