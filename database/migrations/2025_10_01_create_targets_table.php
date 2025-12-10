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
        Schema::create('targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spellEditionId');

            $table->unsignedSmallInteger('type')->index();
            $table->text('description')->nullable();
            $table->boolean('inArea')->default(false);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('perLevel')->nullable();
            $table->unsignedSmallInteger('perLevelMode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
