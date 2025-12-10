<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CharacterClasses\CharacterClassEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characterClassEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('characterClassId');
            $table->foreignIdFor(CharacterClassEdition::class, 'parentId')->nullable();

            $table->string('alternateName')->nullable();
            $table->unsignedSmallInteger('gameEdition');
            $table->text('caption')->nullable();
            $table->boolean('isGroupOnly')->default(false);
            $table->boolean('isPrestige')->default(false);
            $table->unsignedSmallInteger('hitDieFaces')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characterClassEditions');
    }
};
