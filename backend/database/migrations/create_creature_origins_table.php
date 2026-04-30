<?php

declare(strict_types=1);

use App\Models\Creatures\CreatureType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_origins', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->index();
            $table->string('plural');
            $table->string('origin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_origins');
    }
};
