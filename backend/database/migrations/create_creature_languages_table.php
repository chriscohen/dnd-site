<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Languages\Language;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_languages', function (Blueprint $table) {
            $table->uuid('entity_id');
            $table->string('entity_type');
            $table->foreignIdFor(Language::class, 'language_id');
            $table->boolean('can_speak')->default(true);
            $table->boolean('can_hear')->default(true);
            $table->boolean('can_read')->default(true);
            $table->boolean('can_write')->default(true);

            $table->primary(['entity_id', 'entity_type', 'language_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_languages');
    }
};
