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
        Schema::table('services', function (Blueprint $table) {
            // Add main category field (One-time Services or Monthly Subscription)
            if (!Schema::hasColumn('services', 'main_category')) {
                $table->enum('main_category', ['one_time', 'monthly_subscription'])->after('category');
            }
            
            // Add subcategory field for more specific categorization
            if (!Schema::hasColumn('services', 'subcategory')) {
                $table->string('subcategory')->nullable()->after('main_category');
            }
            
            // Add booking advance notice (in hours)
            if (!Schema::hasColumn('services', 'booking_advance_hours')) {
                $table->integer('booking_advance_hours')->default(2)->after('duration');
            }
            
            // Add subscription plan details for monthly services
            if (!Schema::hasColumn('services', 'subscription_plans')) {
                $table->json('subscription_plans')->nullable()->after('booking_advance_hours');
            }
            
            // Add service type specific fields
            if (!Schema::hasColumn('services', 'booking_requirements')) {
                $table->text('booking_requirements')->nullable()->after('subscription_plans');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'main_category',
                'subcategory', 
                'booking_advance_hours',
                'subscription_plans',
                'booking_requirements'
            ]);
        });
    }
};
