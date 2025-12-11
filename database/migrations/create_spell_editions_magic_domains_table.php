<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spell_editions_magic_domains', function (Blueprint $table) {
            $table->uuid('spell_edition_id');
            $table->string('magic_domain_id');

            $table->primary(['spell_edition_id', 'magic_domain_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spell_editions_magic_domains');
    }
};
