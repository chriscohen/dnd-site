<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\CharacterClasses\CharacterClassEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('starting_proficiencies', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(CharacterClassEdition::class, 'character_class_edition_id');

            $table->unsignedSmallInteger('proficiency_type');
            $table->unsignedSmallInteger('value')->nullable();

            $table->uuid('entity_id')->nullable();
            $table->string('entity_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('starting_proficiencies');
    }
};
