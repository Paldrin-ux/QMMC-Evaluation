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
            $table->foreignId('janitor_id')->constrained()->restrictOnDelete();
            $table->foreignId('area_id')->constrained()->restrictOnDelete();
            $table->foreignId('evaluated_by')->constrained('users')->restrictOnDelete();
            $table->date('eval_date');
            $table->string('eval_time');
            $table->string('noted_by')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedTinyInteger('section_a_total')->default(0);
            $table->unsignedTinyInteger('section_b_total')->default(0);
            $table->unsignedTinyInteger('section_c_total')->default(0);
            $table->unsignedTinyInteger('total_score')->default(0);
            $table->string('rating_label')->default('Needs Improvement');

            // ── Signature pads (stored as base64 PNG strings) ──────
            $table->text('sig_evaluated')->nullable(); // drawn signature of evaluator
            $table->text('sig_noted')->nullable();     // drawn signature of noter

            $table->timestamps();
        });

        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->cascadeOnDelete();
            $table->char('section', 1);
            $table->string('field_key', 10);
            $table->boolean('is_compliant');
            $table->unsignedTinyInteger('points_earned');

            // ── Per-row remarks typed in the Remarks column ────────
            $table->string('remarks')->nullable();

            $table->timestamps();
            $table->unique(['evaluation_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_scores');
        Schema::dropIfExists('evaluations');
    }
};