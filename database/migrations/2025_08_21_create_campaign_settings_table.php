<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Company;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaignSettings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('shortName', 16)->unique();
            $table->foreignIdFor(Company::class, 'publisherId')->index();
            $table->smallInteger('publicationType')->index();

            // Images.
            $table->foreignidFor(Media::class, 'logoId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaignSettings');
    }
};
