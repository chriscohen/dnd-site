<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Enums\Sources\SourceContentsType;
use App\Models\Sources\SourceEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SourceEdition::class, 'source_edition_id');

            $table->string('name')->index();
            $table->unsignedSmallInteger('type')->default(SourceContentsType::CHAPTER);
            $table->string('ordinal', 3)->default('1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_contents');
    }
};
