<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AbilityScores\AbilityScoreModifierGroup;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ability_score_modifiers', function (Blueprint $table) {
            $table->unsignedSmallInteger('ability_score');
            $table->smallInteger('value');

            $table->foreignIdFor(AbilityScoreModifierGroup::class, 'ability_score_modifier_group_id');

            $table->unique(['ability_score', 'ability_score_modifier_group_id'], 'ability_score_unique_01');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_score_modifiers');
    }
};
