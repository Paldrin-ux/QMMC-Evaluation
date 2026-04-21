<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('janitorial_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('janitor_name');
            $table->string('area');
            $table->date('date');
            $table->string('time');

            $table->boolean('a1')->default(false);
            $table->boolean('a2')->default(false);
            $table->boolean('a3')->default(false);
            $table->boolean('a4')->default(false);
            $table->boolean('a5')->default(false);
            $table->boolean('a6')->default(false);
            $table->boolean('a7')->default(false);
            $table->boolean('a8')->default(false);
            $table->boolean('a9')->default(false);
            $table->boolean('a10')->default(false);

            $table->boolean('b1')->default(false);
            $table->boolean('b2')->default(false);
            $table->boolean('b3')->default(false);
            $table->boolean('b4')->default(false);
            $table->boolean('b5')->default(false);
            $table->boolean('b6')->default(false);

            $table->boolean('c1')->default(false);
            $table->boolean('c2')->default(false);

            $table->text('comments')->nullable();
            $table->string('evaluated_by')->nullable();
            $table->string('noted_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('janitorial_evaluations');
    }
};