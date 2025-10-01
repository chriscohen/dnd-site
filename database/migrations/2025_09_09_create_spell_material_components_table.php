<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Spells\SpellEdition;
use App\Models\Items\ItemEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_material_components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(ItemEdition::class, 'item_edition_id');
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('quantity_text')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_consumed')->default(false);
            $table->boolean('is_focus')->default(false);
            $table->boolean('is_plural')->default(false);
            $table->unsignedInteger('minimum_value')->nullable();

            $table->unique(['item_edition_id', 'spell_edition_id'], 'spell_edition_item_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_material_components');
    }
};
