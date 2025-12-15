<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Tag;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class, 'tag_id');
            $table->string('entity_id');
            $table->string('entity_type');

            $table->primary(['tag_id', 'entity_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
