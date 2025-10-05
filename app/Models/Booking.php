<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'maid_id',
        'service_id',
        'booking_reference',
        'booking_date',
        'booking_time',
        'duration_hours',
        'total_amount',
        'discount_amount',
        'final_amount',
        'status',
        'customer_notes',
        'maid_notes',
        'admin_notes',
        'address_details',
        'service_requirements',
        'confirmed_at',
        'started_at',
        'completed_at',
        'address',
        'phone',
        'special_instructions',
        'allocated_at',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'string',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'allocated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maid()
    {
        return $this->belongsTo(Maid::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function workHours()
    {
        return $this->hasMany(MaidWorkHour::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function getBookingDateTimeAttribute()
    {
        if (!$this->booking_date) {
            return 'N/A';
        }
        return $this->booking_date->format('Y-m-d') . ' ' . $this->booking_time;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'in_progress' => 'info',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getAddressDetailsAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        return is_array($value) ? $value : null;
    }

    public function getServiceRequirementsAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        return is_array($value) ? $value : null;
    }
}