<?php

declare(strict_types=1);

use App\Models\Prerequisites\PrerequisiteGroup;
use App\Models\Species\Species;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisite_species', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(PrerequisiteGroup::class, 'prerequisite_species_group_id');
            $table->foreignIdFor(Species::class, 'species_id')->nullable();
            $table->string('name')->nullable();

            $table->unique(['prerequisite_species_group_id', 'species_id'], 'species_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisite_species');
    }
};
