<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellEditionLevels', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(SpellEdition::class, 'spellEditionId');
            $table->string('entityId');
            $table->string('entityType');
            $table->unsignedSmallInteger('level')->index();

            $table->unique(['spellEditionId', 'entityId', 'entityType'], 'spellEditionLevelIndex');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellEditionLevels');
    }
};
