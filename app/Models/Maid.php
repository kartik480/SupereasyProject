<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maid extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'profile_image',
        'date_of_birth',
        'gender',
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
        'reference_phone',
        'bio',
        'skills',
        'languages',
        'hourly_rate',
        'rating',
        'total_ratings',
        'status',
        'is_verified',
        'is_active',
        'working_hours',
        'service_areas',
        'specialization',
        'experience_years',
        'completed_bookings',
        'is_available',
        'service_categories',
        'verification_status',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'skills' => 'array',
        'languages' => 'array',
        'working_hours' => 'array',
        'service_areas' => 'array',
        'service_categories' => 'array',
        'verified_at' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function workHours()
    {
        return $this->hasMany(MaidWorkHour::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? asset('storage/' . $this->profile_image) : 'https://via.placeholder.com/150x150/6c757d/ffffff?text=Maid';
    }

    public function getAverageRatingAttribute()
    {
        return $this->total_ratings > 0 ? round($this->rating / $this->total_ratings, 2) : 0;
    }

    public function getDocumentStatusAttribute()
    {
        $documents = [
            'aadhar_card' => !empty($this->aadhar_card),
            'pan_card' => !empty($this->pan_card),
            'address_proof_document' => !empty($this->address_proof_document),
            'police_verification' => !empty($this->police_verification),
            'medical_certificate' => !empty($this->medical_certificate),
        ];
        
        $completed = array_sum($documents);
        $total = count($documents);
        
        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0,
            'documents' => $documents
        ];
    }

    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    public function isMarried()
    {
        return $this->marital_status === 'married';
    }

    public function getFamilyContactAttribute()
    {
        if ($this->isMarried() && $this->husband_name) {
            return $this->husband_name;
        } elseif (!$this->isMarried() && $this->father_name) {
            return $this->father_name;
        }
        return null;
    }
}