<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spellEditionsMagicDomains', function (Blueprint $table) {
            $table->uuid('spellEditionId');
            $table->string('magicDomainId');

            $table->primary(['spellEditionId', 'magicDomainId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spellEditionsMagicDomains');
    }
};
