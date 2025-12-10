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
        Schema::create('entityCategory', function (Blueprint $table) {
            $table->foreignIdFor(Category::class, 'categoryId');
            $table->string('entityId');
            $table->string('entityType');

            $table->primary(['categoryId', 'entityId', 'entityType']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entityCategory');
    }
};
