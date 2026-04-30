<?php

declare(strict_types=1);

use App\Models\Sources\SourceEdition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(SourceEdition::class, 'source_edition_id');
            $table->unsignedSmallInteger('page_from')->nullable();
            $table->unsignedSmallInteger('page_to')->nullable();

            $table->uuid('entity_id');
            $table->string('entity_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
