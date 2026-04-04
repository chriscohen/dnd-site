<?php

declare(strict_types=1);

use App\Models\Creatures\CreatureType;
use App\Models\Prerequisites\PrerequisiteGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisite_creatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(PrerequisiteGroup::class, 'prerequisite_creature_group_id');
            $table->foreignIdFor(CreatureType::class, 'creature_type_id')->nullable();
            $table->string('name')->nullable();

            $table->unique(['prerequisite_creature_group_id', 'creature_type_id'], 'creature_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisite_creatures');
    }
};
