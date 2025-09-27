<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feat_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('feat_id')->unique();

            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feat_editions');
    }
};
