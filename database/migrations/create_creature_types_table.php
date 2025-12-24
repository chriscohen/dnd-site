<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureMajorType;
use App\Models\Creatures\CreatureOrigin;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_types', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedSmallInteger('game_edition');
            $table->foreignIdFor(CreatureMajorType::class, 'creature_major_type_id');
            $table->foreignIdFor(CreatureOrigin::class, 'creature_origin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_types');
    }
};
