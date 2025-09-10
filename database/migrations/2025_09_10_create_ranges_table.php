<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('is_self')->default(false);
            $table->boolean('is_touch')->default(false);
            $table->smallInteger('number')->nullable();
            $table->unsignedSmallInteger('per_level')->nullable();
            $table->unsignedSmallInteger('per_level_increment')->default(1);
            $table->unsignedSmallInteger('unit')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranges');
    }
};
