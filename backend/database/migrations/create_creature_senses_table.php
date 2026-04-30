<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_senses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id');
            $table->string('parent_type');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('range')->nullable();
            $table->unsignedSmallInteger('distance_unit')->nullable();
            $table->text('description')->nullable();

            $table->unique(['parent_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_senses');
    }
};
