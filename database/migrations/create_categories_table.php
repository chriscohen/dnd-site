<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Media\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            // Images.
            $table->foreignIdFor(Media::class, 'image_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
