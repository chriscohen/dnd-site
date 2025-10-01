<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_classes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->boolean('is_prestige')->default(false);
            $table->foreignIdFor(Media::class, 'image_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_classes');
    }
};
