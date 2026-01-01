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
        Schema::create('character_classes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();

            $table->foreignIdFor(Media::class, 'image_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_classes');
    }
};
