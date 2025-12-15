<?php

declare(strict_types=1);

use App\Models\Creatures\Creature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();

            $table->foreignIdFor(Creature::class, 'parent_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatures');
    }
};
