<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_sizes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('size');

            $table->string('parent_id');
            $table->string('parent_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_sizes');
    }
};
