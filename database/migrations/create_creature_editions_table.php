<?php

declare(strict_types=1);

use App\Models\Creatures\Creature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AbilityScores\AbilityScore;
use App\Models\ArmorClass\ArmorClass;
use App\Models\Creatures\CreatureHitPoints;
use App\Enums\GameEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Creature::class, 'creature_id');

            $table->foreignIdFor(AbilityScore::class, 'str_id')->nullable();
            $table->foreignIdFor(AbilityScore::class, 'dex_id')->nullable();
            $table->foreignIdFor(AbilityScore::class, 'con_id')->nullable();
            $table->foreignIdFor(AbilityScore::class, 'int_id')->nullable();
            $table->foreignIdFor(AbilityScore::class, 'wis_id')->nullable();
            $table->foreignIdFor(AbilityScore::class, 'cha_id')->nullable();

            $table->foreignIdFor(ArmorClass::class, 'armor_class_id')->nullable();
            $table->unsignedSmallInteger('challenge_rating')->nullable()->index();
            $table->foreignIdFor(CreatureHitPoints::class, 'creature_hit_points_id')->nullable();
            $table->json('damage_immunities')->nullable();
            $table->json('damage_resistances')->nullable();
            $table->unsignedSmallInteger('game_edition')->default(GameEdition::FIFTH);
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('height_modifier')->nullable();
            $table->boolean('has_fixed_proficiency_bonus')->default(false);
            $table->boolean('is_playable')->default(false);
            $table->unsignedSmallInteger('proficiency_bonus')->nullable();
            $table->json('sizes')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('weight_modifier')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_editions');
    }
};
