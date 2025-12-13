<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ability_score_modifier_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('parent_id');
            $table->string('parent_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_score_modifier_groups');
    }
};
