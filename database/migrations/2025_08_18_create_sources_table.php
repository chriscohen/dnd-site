<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use App\Models\CampaignSetting;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->string('shortName')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('productCode')->nullable();
            $table->smallInteger('sourceType')->index();
            $table->smallInteger('gameEdition')->nullable()->index();
            $table->smallInteger('publicationType')->index();
            $table->foreignIdFor(Company::class, 'publisherId')->nullable()->index();
            $table->foreignIdFor(CampaignSetting::class, 'campaignSettingId')->nullable()->index();
            $table->string('parentId')->nullable()->index();

            // Images.
            $table->foreignIdFor(Media::class, 'coverImageId')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
