<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('collectionName')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('filename');
            $table->string('mimeType')->nullable();
            $table->string('disk', 16);
            $table->unsignedBigInteger('size')->nullable();

            $table->nullableTimestamps();
        });
    }
};
