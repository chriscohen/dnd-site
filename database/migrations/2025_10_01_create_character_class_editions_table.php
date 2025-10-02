<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\CharacterClasses\CharacterClassEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_class_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('character_class_id');
            $table->foreignIdFor(CharacterClassEdition::class, 'parent_id')->nullable();

            $table->string('alternate_name')->nullable();
            $table->unsignedSmallInteger('game_edition');
            $table->text('caption')->nullable();
            $table->boolean('is_group_only')->default(false);
            $table->boolean('is_prestige')->default(false);
            $table->unsignedSmallInteger('hit_die_faces')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_class_editions');
    }
};
