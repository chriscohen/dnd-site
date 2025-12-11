<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_major_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('plural')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_major_types');
    }
};
