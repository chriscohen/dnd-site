<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\Spell;
use App\Models\Range;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(Spell::class, 'spell_id');

            $table->text('description')->nullable();
            $table->text('focus')->nullable();
            $table->unsignedSmallInteger('game_edition')->index();
            $table->text('higher_level')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('magic_school_id')->index();
            $table->unsignedSmallInteger('material_component_mode')->nullable();

            $table->foreignIdFor(Range::class, 'range_id');
            $table->string('spell_components', 10);

            $table->unique(['spell_id', 'game_edition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions');
    }
};
