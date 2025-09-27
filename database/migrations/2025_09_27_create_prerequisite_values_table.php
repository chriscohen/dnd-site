<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Prerequisites\Prerequisite;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisite_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Prerequisite::class, 'prerequisite_id');

            $table->string('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisite_values');
    }
};
