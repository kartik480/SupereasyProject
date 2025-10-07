<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maids', function (Blueprint $table) {
            // First, clear any existing data in service_categories
            DB::statement('UPDATE maids SET service_categories = NULL');
            
            // Then change the column type
            $table->json('service_categories')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maids', function (Blueprint $table) {
            // Change service_categories back to string
            $table->string('service_categories')->nullable()->change();
        });
    }
};