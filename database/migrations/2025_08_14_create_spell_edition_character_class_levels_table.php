<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\SpellEdition;
use App\Models\CharacterClass;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_edition_cc_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');
            $table->foreignIdFor(CharacterClass::class, 'character_class_id');
            $table->unsignedSmallInteger('level')->index();

            $table->unique(['spell_edition_id', 'character_class_id'], 'spell_edition_cc_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_edition_cc_levels');
    }
};
