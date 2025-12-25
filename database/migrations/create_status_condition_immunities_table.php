<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureEdition;
use App\Models\Conditions\ConditionEdition;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('status_condition_immunities', function (Blueprint $table) {
            $table->foreignIdFor(ConditionEdition::class, 'status_condition_edition_id');
            $table->foreignIdFor(CreatureEdition::class, 'creature_edition_id');
            $table->primary(['status_condition_edition_id', 'creature_edition_id'], 'status_condition_creature');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_condition_immunities');
    }
};
