<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('durations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id');
            $table->string('entity_type');

            $table->boolean('concentration')->default(false);
            $table->unsignedSmallInteger('value')->nullable();
            $table->unsignedSmallInteger('unit');
            $table->unsignedSmallInteger('per_level')->nullable();
            $table->unsignedSmallInteger('per_level_mode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durations');
    }
};
