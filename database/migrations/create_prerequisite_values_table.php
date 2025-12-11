<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Prerequisites\Prerequisite;
use App\Models\Language;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisite_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Prerequisite::class, 'prerequisite_id');

            $table->string('value');
            $table->smallInteger('skill_ranks')->nullable();
            $table->unsignedSmallInteger('craft_type')->nullable();
            $table->unsignedSmallInteger('knowledge_type')->nullable();
            $table->unsignedSmallInteger('weapon_focus_type')->nullable();
            $table->foreignIdFor(Language::class, 'language_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisite_values');
    }
};
