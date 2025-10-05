<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['maid_id']);
            
            // Make maid_id nullable
            $table->foreignId('maid_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('maid_id')->references('id')->on('maids')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the nullable foreign key constraint
            $table->dropForeign(['maid_id']);
            
            // Revert maid_id to not nullable
            $table->foreignId('maid_id')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('maid_id')->references('id')->on('maids')->onDelete('cascade');
        });
    }
};
