<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\MovementSpeeds\MovementSpeedGroup;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_speeds', function (Blueprint $table) {
            $table->boolean('can_hover')->default(false);
            $table->unsignedSmallInteger('type');
            $table->foreignIdFor(MovementSpeedGroup::class, 'movement_speed_group_id');
            $table->unsignedSmallInteger('speed');

            $table->unique(['type', 'movement_speed_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_speeds');
    }
};
