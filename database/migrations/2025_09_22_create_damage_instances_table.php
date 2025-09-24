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

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('die_quantity')->nullable();
            $table->unsignedSmallInteger('die_quantity_maximum')->nullable();
            $table->unsignedSmallInteger('die_faces');
            $table->unsignedSmallInteger('damage_type')->nullable();
            $table->smallInteger('modifier')->default(0);
            $table->unsignedSmallInteger('attribute_modifier')->nullable();
            $table->unsignedSmallInteger('attribute_modifier_quantity')->nullable();
            $table->foreignIdFor(StatusConditionEdition::class, 'status_condition_edition_id')->nullable();
            $table->unsignedSmallInteger('per_level_mode')->default(0);

            $table->index(['entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_instances');
    }
};
