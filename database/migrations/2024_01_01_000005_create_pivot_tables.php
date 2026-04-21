<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('janitor_area', function (Blueprint $table) {
            $table->foreignId('janitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->primary(['janitor_id', 'area_id']);
        });

        Schema::create('evaluator_janitor', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('janitor_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'janitor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluator_janitor');
        Schema::dropIfExists('janitor_area');
    }
};