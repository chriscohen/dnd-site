<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellTargets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->boolean('allInArea')->nullable();
            $table->unsignedSmallInteger('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellTargets');
    }
};
