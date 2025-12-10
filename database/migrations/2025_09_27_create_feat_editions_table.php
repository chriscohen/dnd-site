<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('featId')->unique();

            $table->unsignedSmallInteger('gameEdition')->index();
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featEditions');
    }
};
