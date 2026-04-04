<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Actors\ActorType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actor_type_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(ActorType::class, 'actor_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actor_type_editions');
    }
};
