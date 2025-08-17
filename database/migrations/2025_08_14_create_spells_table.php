<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_component_types', function (Blueprint $table) {
            $table->string('id', 1)->primary();
            $table->string('name')->unique();
        });

        Schema::create('spells', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('index')->unique();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->text('higher_level')->nullable();
            $table->smallInteger('range')->index();
        });

        Schema::create('spells_spell_component_types', function (Blueprint $table) {
            $table->uuid('spell_id')->index();
            $table->foreign('spell_id')
                ->references('id')
                ->on('spells');
            $table->string('spell_component_type_id', 1)->index();
            $table->foreign('spell_component_type_id')
                ->references('id')
                ->on('spell_component_types');

            $table->index(['spell_id', 'spell_component_type_id'], 'spells_spell_component_types_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_component_types');
        Schema::dropIfExists('spells');
    }
};
