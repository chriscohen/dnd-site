<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aliases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->uuid('entityId');
            $table->string('entityType');

            $table->index(['entityId', 'entityType']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aliases');
    }
};
