<?php

declare(strict_types=1);

use App\Models\Media\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magic_schools', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->foreignIdFor(Media::class, 'image_id')->nullable();
            $table->string('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->string('short_name', 8)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magic_schools');
    }
};
