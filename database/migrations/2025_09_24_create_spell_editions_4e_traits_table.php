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
        Schema::create('spell_editions_4e_traits', function (Blueprint $table) {
            $table->foreignIdFor(SpellEdition4e::class, 'spell_edition_4e_id');
            $table->foreignIdFor(SpellTrait::class, 'spell_trait_id');

            $table->primary(['spell_edition_4e_id', 'spell_trait_id'], 'edition_trait');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions_4e_traits');
    }
};
