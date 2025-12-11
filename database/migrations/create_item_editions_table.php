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
        Schema::create('item_editions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Item::class, 'item_id');
            $table->smallInteger('game_edition');

            //$table->morphs('itemable');

            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->unsignedInteger('price')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedSmallInteger('rarity');
            $table->float('weight')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_editions');
    }
};
