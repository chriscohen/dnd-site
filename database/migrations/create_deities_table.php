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
        Schema::create('deities', function (Blueprint $table) {
            $table->string('id')->primary();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deities');
    }
};
