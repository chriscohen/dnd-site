<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Feats\FeatureEdition;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(FeatureEdition::class, 'feat_edition_id');

            $table->string('type', 32);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisites');
    }
};
