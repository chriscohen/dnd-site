<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Choices\Choice;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('choice_options', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignIdFor(Choice::class, 'choice_id');

            $table->uuid('entity_id');
            $table->string('entity_type');

            $table->text('description')->nullable();

            $table->unique(['choice_id', 'entity_id', 'entity_type'], 'unique_choice');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choice_options');
    }
};
