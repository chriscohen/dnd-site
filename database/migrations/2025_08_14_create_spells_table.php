<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->index();
            $table->string('name')->index();
            $table->smallInteger('game_edition')->index();
            $table->string('school')->index();
            $table->text('description')->nullable();
            $table->text('higher_level')->nullable();
            $table->smallInteger('range_number')->nullable();
            $table->string('range_unit')->nullable();
            $table->boolean('range_is_touch')->default(false);
            $table->boolean('range_is_self')->default(false);

            $table->unique(['slug', 'game_edition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
