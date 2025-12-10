<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Spells\SpellEdition;
use App\Models\Items\ItemEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellMaterialComponents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(ItemEdition::class, 'itemEditionId');
            $table->foreignIdFor(SpellEdition::class, 'spellEditionId');

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('quantityText')->nullable();
            $table->text('description')->nullable();
            $table->boolean('isConsumed')->default(false);
            $table->boolean('isFocus')->default(false);
            $table->boolean('isPlural')->default(false);
            $table->unsignedInteger('minimumValue')->nullable();

            $table->unique(['itemEditionId', 'spellEditionId'], 'spellEditionItemEdition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellMaterialComponents');
    }
};
