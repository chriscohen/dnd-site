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
        Schema::create('sourceEditionFormats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SourceEdition::class, 'sourceEditionId');
            $table->unsignedSmallInteger('format');

            $table->unique(['source_edition_id', 'format']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sourceEditionFormats');
    }
};
