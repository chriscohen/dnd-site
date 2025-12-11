<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_edition_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');
            $table->string('entity_id');
            $table->string('entity_type');
            $table->unsignedSmallInteger('level')->index();

            $table->unique(['spell_edition_id', 'entity_id', 'entity_type'], 'spell_edition_level_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_edition_levels');
    }
};
