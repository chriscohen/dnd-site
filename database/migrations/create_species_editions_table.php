<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Species\Species;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('species_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Species::class, 'species_id');

            $table->unsignedSmallInteger('game_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('species_editions');
    }
};
