<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Creatures\CreatureEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_alignments', function (Blueprint $table) {
            $table->foreignIdFor(CreatureEdition::class, 'creature_edition_id');
            $table->string('alignment', 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_alignments');
    }
};
