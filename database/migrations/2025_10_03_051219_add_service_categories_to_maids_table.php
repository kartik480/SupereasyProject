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
        Schema::table('maids', function (Blueprint $table) {
            $table->string('service_categories')->nullable()->after('service_areas');
            $table->string('specialization')->nullable()->after('service_categories');
            $table->integer('experience_years')->default(0)->after('specialization');
            $table->integer('completed_bookings')->default(0)->after('experience_years');
            $table->boolean('is_available')->default(true)->after('completed_bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maids', function (Blueprint $table) {
            $table->dropColumn(['service_categories', 'specialization', 'experience_years', 'completed_bookings', 'is_available']);
        });
    }
};
