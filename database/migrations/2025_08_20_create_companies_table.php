<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->foreignIdFor(Media::class, 'logo_id')->nullable();
            $table->string('product_url')->nullable();
            $table->string('short_name')->nullable()->index();
            $table->string('website');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aliases');
    }
};
