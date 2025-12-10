<?php

declare(strict_types=1);

use App\Models\Items\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itemEditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Item::class, 'itemId');
            $table->unsignedSmallInteger('gameEdition');

            $table->text('description')->nullable();
            $table->boolean('isDefault')->default(false);
            $table->boolean('isUnique')->default(false);
            $table->unsignedInteger('price')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('rarity');
            $table->float('weight')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itemEditions');
    }
};
