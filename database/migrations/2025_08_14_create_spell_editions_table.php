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

            $table->foreignIdFor(Spell::class, 'spellId');

            $table->text('description')->nullable();
            $table->text('focus')->nullable();
            $table->unsignedSmallInteger('gameEdition')->index();
            $table->text('higherLevel')->nullable();
            $table->boolean('isDefault')->default(false);
            $table->string('magicSchoolId')->nullable()->index();
            $table->unsignedSmallInteger('materialComponentMode')->nullable();
            $table->unsignedSmallInteger('rarity')->index();

            // Feat?
            $table->foreignIdFor(Feat::class, 'featId')->nullable();

            // Range / area
            $table->foreignIdFor(Range::class, 'rangeId')->nullable();
            $table->foreignIdFor(Area::class, 'areaId')->nullable();

            // Casting time + duration
            $table->unsignedSmallInteger('castingTimeNumber');
            $table->unsignedSmallInteger('castingTimeUnit');

            // Components
            $table->string('spellComponents', 10)->nullable();
            $table->boolean('hasSpellResistance')->nullable();

            $table->unique(['spellId', 'gameEdition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions');
    }
};
