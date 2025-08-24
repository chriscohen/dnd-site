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
        Schema::create('campaign_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('short_name', 16)->unique();
            $table->foreignIdFor(Company::class, 'publisher_id')->index();
            $table->smallInteger('publication_type')->index();

            // Images.
            $table->foreignidFor(Media::class, 'logo_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_settings');
    }
};
