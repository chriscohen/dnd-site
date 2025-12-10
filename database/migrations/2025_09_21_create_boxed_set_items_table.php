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
        Schema::create('boxedSetItems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignIdFor(SourceEdition::class, 'parentId');

            $table->unsignedSmallInteger('content_type')->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxedSetItems');
    }
};
