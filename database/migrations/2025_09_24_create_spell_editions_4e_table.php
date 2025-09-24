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
        Schema::create('spell_editions_4e', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');

            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('frequency')->nullable();
            $table->unsignedSmallInteger('attack_attribute')->nullable();
            $table->unsignedSmallInteger('attack_save')->nullable();
            $table->text('hit_primary')->nullable();
            $table->text('hit_secondary')->nullable();
            $table->text('miss')->nullable();
            $table->text('effect')->nullable();

            // targets

            $table->string('special_name')->nullable();
            $table->text('special')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions_4e');
    }
};
