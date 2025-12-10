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
        Schema::create('skillEditions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(Skill::class, 'skillId');

            $table->string('alternateName')->nullable();
            $table->unsignedSmallInteger('gameEdition')->index();
            $table->unsignedSmallInteger('relatedAttribute')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skillEditions');
    }
};
