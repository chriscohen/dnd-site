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
        Schema::create('prerequisiteValues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Prerequisite::class, 'prerequisiteId');

            $table->string('value');
            $table->smallInteger('skillRanks')->nullable();
            $table->unsignedSmallInteger('craftType')->nullable();
            $table->unsignedSmallInteger('knowledgeType')->nullable();
            $table->unsignedSmallInteger('weaponFocusType')->nullable();
            $table->foreignIdFor(Language::class, 'languageId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisiteValues');
    }
};
