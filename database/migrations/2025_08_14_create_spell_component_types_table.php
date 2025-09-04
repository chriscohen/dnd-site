<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_component_types', function (Blueprint $table) {
            $table->string('id', 1)->primary();
            $table->string('name')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_component_types');
    }
};
