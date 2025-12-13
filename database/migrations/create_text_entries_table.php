<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('text_entry_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('text')->nullable();
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('type')->index();

            $table->text('entry_data')->nullable();

            $table->string('parent_id');
            $table->string('parent_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('text_entry_entries');
    }
};
