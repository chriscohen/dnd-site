<?php

declare(strict_types=1);

use App\Models\Creatures\Creature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Creatures\CreatureHitPoints;
use App\Enums\GameEdition;
use App\Models\Creatures\CreatureType;
use App\Models\Sources\Source;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Creature::class, 'creature_id');
            $table->foreignIdFor(Source::class, 'source_id')->nullable();

            $table->float('challenge_rating')->nullable()->index();
            $table->foreignIdFor(CreatureHitPoints::class, 'creature_hit_points_id')->nullable();
            $table->foreignIdFor(CreatureType::class, 'creature_type_id')->nullable();
            $table->json('damage_immunities')->nullable();
            $table->json('damage_resistances')->nullable();
            $table->unsignedSmallInteger('game_edition')->default(GameEdition::FIFTH);
            $table->boolean('has_fixed_proficiency_bonus')->default(false);
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('height_modifier')->nullable();
            $table->unsignedSmallInteger('hit_die_faces')->nullable();
            $table->boolean('is_playable')->default(false);
            $table->unsignedSmallInteger('lair_xp')->nullable();
            $table->unsignedSmallInteger('proficiency_bonus')->nullable();
            $table->json('sizes')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('weight_modifier')->nullable();

            // We can have multiple editions of the same creature, in the same game edition, as long as they come from
            // different sources.
            $table->unique(['creature_id', 'game_edition', 'source_id'], 'creature_edition_source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_editions');
    }
};
