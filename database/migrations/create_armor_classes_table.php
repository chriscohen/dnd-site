<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armor_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('source');
            $table->smallInteger('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armor_classes');
    }
};
