<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_spell_component_type', function (Blueprint $table) {
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
        Schema::dropIfExists('spells_spell_component_types');
    }
};
