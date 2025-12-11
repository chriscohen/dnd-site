<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Spells\SpellEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->boolean('all_in_area')->nullable();
            $table->unsignedSmallInteger('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_targets');
    }
};
