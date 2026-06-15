<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->constrained('prospects')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->foreignId('sub_criteria_id')->nullable()->constrained('sub_criterias')->nullOnDelete();
            $table->decimal('raw_value', 8, 2);
            $table->timestamps();

            $table->unique(['prospect_id', 'criteria_id']);
            $table->index('prospect_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
