<?php

declare(strict_types=1);

use App\Models\StatusConditions\StatusCondition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statusConditionEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(StatusCondition::class, 'statusConditionId');
            $table->unsignedSmallInteger('gameEdition');

            $table->text('description')->nullable();

            $table->unique(['statusConditionId', 'gameEdition'], 'statusEdition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statusConditionEditions');
    }
};
