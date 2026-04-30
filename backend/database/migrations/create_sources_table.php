<?php

declare(strict_types=1);

use App\Models\CampaignSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->string('short_name')->unique()->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('source_type')->index();
            $table->smallInteger('publication_type')->index();
            $table->foreignIdFor(CampaignSetting::class, 'campaign_setting_id')->nullable()->index();
            $table->string('parent_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
