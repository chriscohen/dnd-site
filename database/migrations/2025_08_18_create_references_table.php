<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id');
            $table->string('entity_type');
            $table->uuid('source_id');
            $table->smallInteger('page_from');
            $table->smallInteger('page_to');

            $table->unique(
                ['entity_id', 'entity_type', 'source_id', 'page_from', 'page_to'],
                'unique_reference'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
