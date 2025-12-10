<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatureTypeEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CreatureType::class, 'creatureTypeId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatureTypeEditions');
    }
};
