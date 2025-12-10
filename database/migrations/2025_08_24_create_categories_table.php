<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Category;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('entity_type')->index();
            $table->foreignIdFor(Category::class, 'parentId')->nullable()->index();

            // Images.
            $table->foreignIdFor(Media::class, 'imageId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
