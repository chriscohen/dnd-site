<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magic_domains', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magic_domains');
    }
};
