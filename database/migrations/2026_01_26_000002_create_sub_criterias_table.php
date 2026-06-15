<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->string('label');
            $table->decimal('value', 8, 2);
            $table->integer('sequence')->default(0);
            $table->timestamps();
            
            $table->index('criteria_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_criterias');
    }
};
