<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Skills\SkillEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_instances', function (Blueprint $table) {
            $table->uuid('entity_id');
            $table->string('entity_type');
            $table->foreignIdFor(SkillEdition::class, 'skill_edition_id');
            $table->unsignedSmallInteger('mastery')->nullable();

            $table->primary(['entity_id', 'entity_type', 'skill_edition_id'], 'entity_skill_edition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_instances');
    }
};
