<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellsClasses', function (Blueprint $table) {
            $table->uuid('spellId');
            $table->string('classId');
            $table->smallInteger('level');

            $table->primary(['spellId', 'classId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellsClasses');
    }
};
