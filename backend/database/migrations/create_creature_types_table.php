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
        Schema::create('creature_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();

            $table->foreignIdFor(CreatureType::class, 'parent_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_types');
    }
};
