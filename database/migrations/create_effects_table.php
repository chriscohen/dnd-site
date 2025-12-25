<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('effects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('type'); // @see EffectType::class

            // The thing that is applying the damage, eg spell edition, item edition.
            $table->uuid('owner_id')->nullable();
            $table->string('owner_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('effects');
    }
};
