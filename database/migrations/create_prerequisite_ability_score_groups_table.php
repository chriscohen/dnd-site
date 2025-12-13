<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Prerequisites\PrerequisiteGroup;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisite_ability_score_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(PrerequisiteGroup::class, 'prerequisite_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisite_ability_score_groups');
    }
};
