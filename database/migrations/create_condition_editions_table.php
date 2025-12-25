<?php

declare(strict_types=1);

use App\Models\Conditions\Condition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condition_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Condition::class, 'condition_id');
            $table->unsignedSmallInteger('game_edition');

            $table->text('description')->nullable();

            $table->unique(['condition_id', 'game_edition'], 'status_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condition_editions');
    }
};
