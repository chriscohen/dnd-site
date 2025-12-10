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

            $table->foreignIdFor(SourceEdition::class, 'sourceEditionId');
            $table->unsignedSmallInteger('pageFrom');
            $table->unsignedSmallInteger('pageTo')->nullable();

            $table->uuid('entityId');
            $table->string('entityType');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
