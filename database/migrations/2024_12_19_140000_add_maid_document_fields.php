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
        Schema::table('maids', function (Blueprint $table) {
            // Personal Information
            if (!Schema::hasColumn('maids', 'marital_status')) {
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('gender');
            }
            
            if (!Schema::hasColumn('maids', 'husband_name')) {
                $table->string('husband_name')->nullable()->after('marital_status');
            }
            
            if (!Schema::hasColumn('maids', 'father_name')) {
                $table->string('father_name')->nullable()->after('husband_name');
            }
            
            // Document Proofs
            if (!Schema::hasColumn('maids', 'aadhar_number')) {
                $table->string('aadhar_number', 12)->nullable()->after('father_name');
            }
            
            if (!Schema::hasColumn('maids', 'aadhar_card')) {
                $table->string('aadhar_card')->nullable()->after('aadhar_number');
            }
            
            if (!Schema::hasColumn('maids', 'pan_number')) {
                $table->string('pan_number', 10)->nullable()->after('aadhar_card');
            }
            
            if (!Schema::hasColumn('maids', 'pan_card')) {
                $table->string('pan_card')->nullable()->after('pan_number');
            }
            
            // Address Proof
            if (!Schema::hasColumn('maids', 'address_proof_type')) {
                $table->string('address_proof_type')->nullable()->after('pan_card');
            }
            
            if (!Schema::hasColumn('maids', 'address_proof_document')) {
                $table->string('address_proof_document')->nullable()->after('address_proof_type');
            }
            
            // Google Maps Integration
            if (!Schema::hasColumn('maids', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address_proof_document');
            }
            
            if (!Schema::hasColumn('maids', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            if (!Schema::hasColumn('maids', 'google_maps_link')) {
                $table->text('google_maps_link')->nullable()->after('longitude');
            }
            
            // Additional Documents
            if (!Schema::hasColumn('maids', 'police_verification')) {
                $table->string('police_verification')->nullable()->after('google_maps_link');
            }
            
            if (!Schema::hasColumn('maids', 'medical_certificate')) {
                $table->string('medical_certificate')->nullable()->after('police_verification');
            }
            
            if (!Schema::hasColumn('maids', 'reference_contact')) {
                $table->string('reference_contact')->nullable()->after('medical_certificate');
            }
            
            if (!Schema::hasColumn('maids', 'reference_phone')) {
                $table->string('reference_phone')->nullable()->after('reference_contact');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maids', function (Blueprint $table) {
            $table->dropColumn([
                'marital_status',
                'husband_name',
                'father_name',
                'aadhar_number',
                'aadhar_card',
                'pan_number',
                'pan_card',
                'address_proof_type',
                'address_proof_document',
                'latitude',
                'longitude',
                'google_maps_link',
                'police_verification',
                'medical_certificate',
                'reference_contact',
                'reference_phone'
            ]);
        });
    }
};
