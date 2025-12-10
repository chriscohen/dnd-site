<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\StatusConditions\StatusConditionEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // The thing that is applying the damage, eg spell edition, item edition.
            $table->uuid('entity_id');
            $table->string('entity_type');

            // # of instances.
            $table->unsignedSmallInteger('quantity')->default(1);

            // Damage numbers.
            $table->unsignedSmallInteger('fixed_damage')->nullable();
            $table->unsignedSmallInteger('die_quantity')->nullable();
            $table->unsignedSmallInteger('die_faces')->nullable();

            // Maximums
            $table->unsignedSmallInteger('die_quantity_maximum')->nullable();
            $table->unsignedSmallInteger('fixed_damage_maximum')->nullable();

            // Type.
            $table->unsignedSmallInteger('damage_type')->nullable();

            // Modifier.
            $table->smallInteger('modifier')->default(0);

            // Attribute modifier.
            $table->unsignedSmallInteger('attribute_modifier')->nullable();
            $table->unsignedSmallInteger('attribute_modifier_quantity')->nullable();

            // Status condition.
            $table->foreignIdFor(StatusConditionEdition::class, 'status_condition_edition_id')->nullable();

            // Per level damage numbers.
            $table->unsignedSmallInteger('per_level_die_quantity')->nullable();
            $table->unsignedSmallInteger('per_level_die_faces')->nullable();
            $table->unsignedSmallInteger('per_level_fixed_damage')->nullable();
            $table->unsignedSmallInteger('per_level_mode')->nullable();

            $table->index(['entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_instances');
    }
};
