<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\Spell;
use App\Models\Range;
use App\Models\Feats\Feat;
use App\Models\Area;

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
            $table->string('magic_school_id')->nullable()->index();
            $table->unsignedSmallInteger('material_component_mode')->nullable();

            // Feat?
            $table->foreignIdFor(Feat::class, 'feat_id')->nullable();

            // Range / area
            $table->foreignIdFor(Range::class, 'range_id');
            $table->foreignIdFor(Area::class, 'area_id')->nullable();

            // Casting time + duration
            $table->unsignedSmallInteger('casting_time_number');
            $table->unsignedSmallInteger('casting_time_unit');

            // Components
            $table->string('spell_components', 10)->nullable();
            $table->boolean('has_spell_resistance')->nullable();

            $table->unique(['spell_id', 'game_edition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions');
    }
};
