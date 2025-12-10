<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureMajorType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatureMajorTypeEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CreatureMajorType::class, 'creatureMajorTypeId');
            $table->unsignedSmallInteger('gameEdition')->index();
            $table->text('description')->nullable();

            $table->unique(['creatureMajorTypeId', 'gameEdition'], 'creatureEditionUnique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatureMajorTypeEditions');
    }
};
