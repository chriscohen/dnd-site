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
        Schema::create('spellEditions4e', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spellEditionId');

            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('frequency')->nullable();
            $table->unsignedSmallInteger('attackAttribute')->nullable();
            $table->unsignedSmallInteger('attackSave')->nullable();
            $table->text('hitPrimary')->nullable();
            $table->text('hitSecondary')->nullable();
            $table->text('miss')->nullable();
            $table->text('effect')->nullable();

            // targets

            $table->string('specialNname')->nullable();
            $table->text('special')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellEditions4e');
    }
};
