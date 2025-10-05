<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maid_work_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maid_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->date('work_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->integer('total_hours')->default(0);
            $table->decimal('earnings', 10, 2)->default(0);
            $table->enum('status', ['checked_in', 'checked_out', 'break', 'completed'])->default('checked_in');
            $table->text('notes')->nullable();
            $table->json('location_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maid_work_hours');
    }
};