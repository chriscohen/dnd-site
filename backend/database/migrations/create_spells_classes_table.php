<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spells_classes', function (Blueprint $table) {
            $table->uuid('spell_id');
            $table->string('class_id');
            $table->smallInteger('level');

            $table->primary(['spell_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells_classes');
    }
};
