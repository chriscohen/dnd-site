<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SourceEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_edition_formats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SourceEdition::class, 'source_edition_id');
            $table->unsignedSmallInteger('format');

            $table->unique(['source_edition_id', 'format']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_edition_formats');
    }
};
