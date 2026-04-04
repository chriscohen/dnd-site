<?php

declare(strict_types=1);

use App\Models\People\Person;
use App\Models\Sources\SourceEdition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Person::class, 'person_id');
            $table->foreignIdFor(SourceEdition::class, 'source_edition_id');
            $table->string('role');

            $table->unique(['person_id', 'source_edition_id', 'role'], 'person_book_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
