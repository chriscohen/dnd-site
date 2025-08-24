<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use App\Models\CampaignSetting;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('product_code')->nullable();
            $table->smallInteger('source_type')->index();
            $table->smallInteger('game_edition')->nullable()->index();
            $table->smallInteger('publication_type')->index();
            $table->string('cover_image')->nullable();
            $table->foreignIdFor(Company::class, 'publisher_id')->nullable()->index();
            $table->foreignIdFor(CampaignSetting::class, 'campaign_setting_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
