<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sources\SourceEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxed_set_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignIdFor(SourceEdition::class, 'parent_id');

            $table->unsignedSmallInteger('contentType')->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxed_set_items');
    }
};
