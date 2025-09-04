<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\Spell;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(Spell::class, 'spell_id');

            $table->unsignedSmallInteger('game_edition')->index();
            $table->string('magic_school_id')->index();
            $table->text('description')->nullable();
            $table->text('higher_level')->nullable();

            $table->unsignedSmallInteger('range_number')->nullable();
            $table->unsignedSmallInteger('range_unit')->nullable();
            $table->boolean('range_is_touch')->default(false);
            $table->boolean('range_is_self')->default(false);

            $table->unique(['spell_id', 'game_edition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions');
    }
};
