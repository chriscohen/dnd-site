<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Skills\Skill;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_editions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(Skill::class, 'skill_id');

            $table->string('alternate_name')->nullable();
            $table->unsignedSmallInteger('game_edition')->index();
            $table->unsignedSmallInteger('related_ability')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_editions');
    }
};
