<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaidWorkHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'maid_id',
        'booking_id',
        'work_date',
        'check_in_time',
        'check_out_time',
        'total_hours',
        'earnings',
        'status',
        'notes',
        'location_data',
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'earnings' => 'decimal:2',
        'location_data' => 'array',
    ];

    public function maid()
    {
        return $this->belongsTo(Maid::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeToday($query)
    {
        return $query->where('work_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('work_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('work_date', now()->month)
                    ->whereYear('work_date', now()->year);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getDurationAttribute()
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInHours($this->check_out_time);
        }
        return $this->total_hours;
    }

    public function getEarningsPerHourAttribute()
    {
        return $this->total_hours > 0 ? $this->earnings / $this->total_hours : 0;
    }
}