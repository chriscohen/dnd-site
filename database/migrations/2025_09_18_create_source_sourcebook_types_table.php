<?php

declare(strict_types=1);

use App\Models\Sources\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_sourcebook_types', function (Blueprint $table) {
            $table->foreignIdFor(Source::class, 'source_id');
            $table->unsignedSmallInteger('sourcebook_type');
            $table->primary(['source_id', 'sourcebook_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_sourcebook_types');
    }
};
