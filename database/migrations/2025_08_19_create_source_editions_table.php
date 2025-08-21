<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('source_id')
                ->references('id')
                ->on('sources');
            $table->string('name');
            $table->smallInteger('binding')->nullable();
            $table->smallInteger('pages')->nullable();
            $table->string('isbn10', 10)->unique()->nullable();
            $table->string('isbn13', 13)->unique()->nullable();
            $table->date('release_date')->nullable();
            $table->boolean('release_date_month_only')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
