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
        Schema::create('sourceSourcebookTypes', function (Blueprint $table) {
            $table->foreignIdFor(Source::class, 'sourceId');
            $table->unsignedSmallInteger('sourcebookType');
            $table->primary(['sourceId', 'sourcebookType']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sourceSourcebookTypes');
    }
};
