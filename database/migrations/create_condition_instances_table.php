<?php

declare(strict_types=1);

use App\Models\Conditions\ConditionEdition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condition_instances', function (Blueprint $table) {
            $table->uuid('entity_id');
            $table->string('entity_type');
            $table->foreignIdFor(ConditionEdition::class, 'condition_edition_id')->nullable();

            // @see ConditionInstanceType::class
            $table->unsignedSmallInteger('type');

            $table->unsignedSmallInteger('damage_type')->nullable();
            $table->json('damage_source_types')->nullable();
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condition_instances');
    }
};
