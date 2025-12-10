<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\SpellEdition4e;
use App\Models\Spells\SpellTrait;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellEditions4eTraits', function (Blueprint $table) {
            $table->foreignIdFor(SpellEdition4e::class, 'spellEdition4eId');
            $table->foreignIdFor(SpellTrait::class, 'spellTraitId');

            $table->primary(['spellEdition4eId', 'spellTraitId'], 'editionTrait');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellEditions4eTraits');
    }
};
