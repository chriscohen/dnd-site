<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // The thing that is applying the damage, eg spell edition, item edition.
            $table->uuid('entity_id');
            $table->string('entity_type');

            $table->unsignedSmallInteger('die_quantity')->nullable();
            $table->unsignedSmallInteger('die_quantity_maximum')->nullable();
            $table->unsignedSmallInteger('die_faces');
            $table->unsignedSmallInteger('damage_type');
            $table->smallInteger('modifier')->default(0);
            $table->unsignedSmallInteger('per_level_mode')->default(0);

            $table->index(['entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_instances');
    }
};
