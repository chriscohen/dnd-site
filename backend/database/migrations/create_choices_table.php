<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedSmallInteger('count')->default(2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
