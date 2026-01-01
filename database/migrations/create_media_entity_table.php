<?php

use App\Models\Media\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_entity', function (Blueprint $table) {
            $table->foreignIdFor(Media::class, 'media_id');
            $table->string('entity_id');
            $table->string('entity_type');

            $table->primary(['media_id', 'entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_entity');
    }
};
