<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ArmorClass\ArmorClass;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armor_class_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(ArmorClass::class, 'armor_class_id');
            $table->unsignedSmallInteger('source');
            $table->smallInteger('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armor_class_items');
    }
};
