<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_type_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('game_edition');
            $table->foreignIdFor(CreatureType::class, 'creature_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_type_editions');
    }
};
