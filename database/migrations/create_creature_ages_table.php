<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureTypeEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_ages', function (Blueprint $table) {
            $table->foreignIdFor(CreatureTypeEdition::class, 'creature_type_edition_id');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('value');

            $table->primary(['creature_type_edition_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_ages');
    }
};
