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
        Schema::create('entity_category', function (Blueprint $table) {
            $table->foreignIdFor(Category::class, 'category_id');
            $table->string('entity_id');
            $table->string('entity_type');

            $table->primary(['category_id', 'entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_category');
    }
};
