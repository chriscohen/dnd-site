<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_hit_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('average')->nullable();
            $table->string('formula')->nullable();
            $table->string('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_hit_points');
    }
};
