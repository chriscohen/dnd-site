<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Category;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('entity_type')->index();
            $table->foreignIdFor(Category::class, 'parent_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
