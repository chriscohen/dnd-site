<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureType;
use App\Models\Sources\Source;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_species_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CreatureType::class, 'creature_type_id');
            $table->unsignedSmallInteger('game_edition');
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('height_modifier')->nullable();
            $table->unsignedSmallInteger('hit_die_faces')->nullable();
            $table->foreignIdFor(Source::class, 'source_id')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('weight_modifier')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_species_editions');
    }
};
