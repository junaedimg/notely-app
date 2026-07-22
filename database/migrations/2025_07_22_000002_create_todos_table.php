<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->nullable()->constrained('notes')->nullOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active');
            $table->boolean('is_important')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->string('repeat_type', 20)->default('none');
            $table->integer('interval_value')->nullable();
            $table->string('interval_unit', 10)->nullable();
            $table->json('days_of_week')->nullable();
            $table->integer('day_of_month')->nullable();
            $table->integer('month_of_year')->nullable();
            $table->string('repeat_anchor', 20)->default('schedule');
            $table->string('end_type', 10)->default('never');
            $table->timestamp('end_date')->nullable();
            $table->integer('end_count')->nullable();
            $table->integer('completed_count')->default(0);
            $table->timestamp('next_due_at')->nullable();
            $table->string('reminder_time', 5)->nullable();
            $table->timestamp('paused_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
