<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distances', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('plural')->nullable()->index();
            $table->string('short_name')->unique();
            $table->double('per_meter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distances');
    }
};
