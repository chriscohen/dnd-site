<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_casting_times', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');
            $table->unsignedSmallInteger('number');
            $table->unsignedSmallInteger('unit'); // @see TimeUnit::class

            $table->unsignedSmallInteger('plus')->nullable();
            $table->unsignedSmallInteger('plus_type')->nullable(); // @see PerLevelMode::class

            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_casting_times');
    }
};
