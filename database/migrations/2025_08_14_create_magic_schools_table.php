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
        Schema::create('magic_schools', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->foreignIdFor(Media::class, 'image_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magic_schools');
    }
};
