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
        Schema::table('bookings', function (Blueprint $table) {
            // Add missing columns (maid_id is already nullable in the original table)
            $table->text('address')->nullable()->after('booking_time');
            $table->string('phone')->nullable()->after('address');
            $table->text('special_instructions')->nullable()->after('phone');
            $table->timestamp('allocated_at')->nullable()->after('completed_at');
            $table->timestamp('cancelled_at')->nullable()->after('allocated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['address', 'phone', 'special_instructions', 'allocated_at', 'cancelled_at']);
        });
    }
};
