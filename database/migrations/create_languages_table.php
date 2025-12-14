<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Languages\LanguageGroup;
use App\Models\Languages\LanguageScript;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();

            $table->boolean('is_exotic')->default(false);
            $table->boolean('is_srd')->default(false);
            $table->string('origin')->nullable();

            $table->foreignIdFor(LanguageScript::class, 'script_id')->nullable();
            $table->foreignIdFor(LanguageGroup::class, 'language_group_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
