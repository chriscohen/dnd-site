<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->index();
            $table->string('name')->index();
            $table->foreignIdFor(Media::class, 'image_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
