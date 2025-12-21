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
        Schema::create('backgrounds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->foreignIdFor(SourceEdition::class, 'source_edition_id');
            $table->string('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backgrounds');
    }
};
