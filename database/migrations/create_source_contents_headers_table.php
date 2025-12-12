<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Sources\SourceContents;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_contents_headers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SourceContents::class, 'source_contents_id');
            $table->string('header')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_contents_headers');
    }
};
