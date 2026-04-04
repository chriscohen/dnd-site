<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Creatures\CreatureTypeEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armor_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(CreatureTypeEdition::class, 'creature_type_edition_id');
            $table->text('condition')->nullable();
            $table->boolean('braces')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armor_classes');
    }
};
