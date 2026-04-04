<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ability_scores', function (Blueprint $table) {
            $table->uuid('parent_id');
            $table->string('parent_type');
            $table->boolean('is_proficient')->default(false);
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('value');

            $table->primary(['parent_id', 'parent_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_scores');
    }
};
