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
        Schema::create('damageInstances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // The thing that is applying the damage, eg spell edition, item edition.
            $table->uuid('entityId');
            $table->string('entityType');

            // # of instances.
            $table->unsignedSmallInteger('quantity')->default(1);

            // Damage numbers.
            $table->unsignedSmallInteger('fixedDamage')->nullable();
            $table->unsignedSmallInteger('dieQuantity')->nullable();
            $table->unsignedSmallInteger('dieFaces')->nullable();

            // Maximums
            $table->unsignedSmallInteger('dieQuantityMaximum')->nullable();
            $table->unsignedSmallInteger('fixedDamageMaximum')->nullable();

            // Type.
            $table->unsignedSmallInteger('damageType')->nullable();

            // Modifier.
            $table->smallInteger('modifier')->default(0);

            // Attribute modifier.
            $table->unsignedSmallInteger('attributeModifier')->nullable();
            $table->unsignedSmallInteger('attributeModifierQuantity')->nullable();

            // Status condition.
            $table->foreignIdFor(StatusConditionEdition::class, 'statusConditionEditionId')->nullable();

            // Per level damage numbers.
            $table->unsignedSmallInteger('perLevelDieQuantity')->nullable();
            $table->unsignedSmallInteger('perLevelDieFaces')->nullable();
            $table->unsignedSmallInteger('perLevelFixedDamage')->nullable();
            $table->unsignedSmallInteger('perLevelMode')->nullable();

            $table->index(['entityId', 'entityType']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damageInstances');
    }
};
