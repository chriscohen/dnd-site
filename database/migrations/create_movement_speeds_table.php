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
            $table->uuid('parent_id');
            $table->string('parent_type');
            $table->unsignedSmallInteger('type');
            $table->unsignedSmallInteger('value');

            $table->unique(['type', 'parent_id', 'parent_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_speeds');
    }
};
