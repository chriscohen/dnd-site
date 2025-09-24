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
        Schema::create('status_condition_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(StatusCondition::class, 'status_condition_id');
            $table->unsignedSmallInteger('game_edition');

            $table->text('description')->nullable();

            $table->unique(['status_condition_id', 'game_edition'], 'status_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_condition_editions');
    }
};
