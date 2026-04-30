<?php

declare(strict_types=1);

use App\Models\Effects\Effect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Conditions\ConditionEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(Effect::class, 'effect_id');

            // # of instances of damage.
            $table->unsignedSmallInteger('quantity')->default(1);

            // Dice.
            $table->string('formula')->nullable(); // @see DiceFormula::class

            $table->unsignedSmallInteger('damage_type')->nullable();
            $table->foreignIdFor(ConditionEdition::class, 'condition_edition_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_instances');
    }
};
