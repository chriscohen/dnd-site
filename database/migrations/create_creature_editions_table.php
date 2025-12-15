<?php

declare(strict_types=1);

use App\Models\Creatures\Creature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Creature::class, 'creature_id');

            $table->unsignedSmallInteger('game_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_editions');
    }
};
