<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sourceEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sourceId')
                ->references('id')
                ->on('sources');
            $table->string('name');
            $table->smallInteger('binding')->nullable();
            $table->boolean('isPrimary')->default(false)->index();
            $table->string('isbn10', 10)->unique()->nullable();
            $table->string('isbn13', 13)->unique()->nullable();
            $table->smallInteger('pages')->nullable();
            $table->date('releaseDate')->nullable();
            $table->boolean('releaseDateMonthOnly')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sourceEditions');
    }
};
