<?php

declare(strict_types=1);

use App\Models\Spells\SpellComponentType;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_edition_spell_component_types', function (Blueprint $table) {
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');
            $table->foreignIdFor(SpellComponentType::class, 'spell_component_type_id');

            $table->index(['spell_edition_id', 'spell_component_type_id'], 'spell_edition_components_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_edition_spell_component_types');
    }
};
