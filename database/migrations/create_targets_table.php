<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(SpellEdition::class, 'spell_edition_id');

            $table->unsignedSmallInteger('type')->index();
            $table->text('description')->nullable();
            $table->boolean('in_area')->default(false);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('per_level')->nullable();
            $table->unsignedSmallInteger('per_level_mode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
