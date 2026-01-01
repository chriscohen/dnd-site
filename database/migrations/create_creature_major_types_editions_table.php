<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureMainType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_major_type_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('alternate_name')->nullable();
            $table->foreignIdFor(CreatureMainType::class, 'creature_major_type_id');
            $table->unsignedSmallInteger('game_edition')->index();
            $table->text('description')->nullable();

            $table->unique(['creature_major_type_id', 'game_edition'], 'creature_edition_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_major_type_editions');
    }
};
