<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Person;
use App\Models\Sources\SourceEdition;

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

            $table->unique(['person_id', 'source_edition_id', 'role'], 'person_book_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backgrounds');
    }
};
