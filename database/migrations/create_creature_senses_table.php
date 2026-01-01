<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Creatures\CreatureTypeEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_senses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CreatureTypeEdition::class, 'creature_type_edition_id');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('range')->nullable();
            $table->unsignedSmallInteger('distance_unit')->nullable();
            $table->text('description')->nullable();

            $table->unique(['creature_type_edition_id', 'type'], 'creature_edition_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_senses');
    }
};
