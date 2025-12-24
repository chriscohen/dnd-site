<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('text_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('text')->nullable();
            $table->string('name')->nullable();

            // The order of items determines which appears first, second, third, etc.
            $table->unsignedSmallInteger('order')->index();

            $table->unsignedSmallInteger('type')->index();
            $table->json('entry_data')->nullable();

            $table->string('parent_id');
            $table->string('parent_type');

            $table->unique(['order', 'parent_id', 'parent_type'], 'order_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('text_entries');
    }
};
