<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->unsignedSmallInteger('size');
            $table->string('parent_id');
            $table->string('parent_type');

            $table->unique(['size', 'parent_id', 'parent_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
