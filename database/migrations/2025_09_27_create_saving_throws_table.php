<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\StatusConditions\StatusConditionEdition;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savingThrows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spellEditionId');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('multiplier')->nullable();
            $table->foreignIdFor(StatusConditionEdition::class, 'failStatusId')->nullable();
            $table->foreignIdFor(StatusConditionEdition::class, 'succeedStatusId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savingThrows');
    }
};
