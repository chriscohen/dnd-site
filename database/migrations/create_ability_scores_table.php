<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ability_scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_scores');
    }
};
