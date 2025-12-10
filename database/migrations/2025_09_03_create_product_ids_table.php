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
        Schema::create('product_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Source::class, 'source_id');
            $table->foreignIdFor(Company::class, 'origin_id');
            $table->string('product_id');

            $table->unique(['origin_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ids');
    }
};
