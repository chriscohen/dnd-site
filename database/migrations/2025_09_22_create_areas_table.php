<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedSmallInteger('length')->nullable();
            $table->unsignedSmallInteger('perLevel')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('radius')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
