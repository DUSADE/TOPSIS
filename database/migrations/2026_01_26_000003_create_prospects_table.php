<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->index();
            $table->enum('status', ['NEW', 'CONTACTED', 'QUALIFIED', 'LOST', 'WON'])->default('NEW')->index();
            $table->decimal('spk_score', 10, 6)->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'spk_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
