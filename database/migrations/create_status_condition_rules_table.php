<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StatusConditions\StatusConditionEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_condition_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(StatusConditionEdition::class, 'status_condition_edition_id');
            $table->text('rule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_condition_rules');
    }
};
