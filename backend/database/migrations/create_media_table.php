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
            $table->string('collection_name')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedSmallInteger('media_type')->index();
            $table->string('disk', 16);
            $table->unsignedBigInteger('size')->nullable();

            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
