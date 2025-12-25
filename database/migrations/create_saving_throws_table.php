<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Conditions\ConditionEdition;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saving_throws', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('multiplier')->nullable();
            $table->foreignIdFor(ConditionEdition::class, 'fail_status_id')->nullable();
            $table->foreignIdFor(ConditionEdition::class, 'succeed_status_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saving_throws');
    }
};
