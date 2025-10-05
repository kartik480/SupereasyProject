<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_price',
        'image',
        'image_2',
        'image_3',
        'image_4',
        'category',
        'main_category',
        'subcategory',
        'duration',
        'booking_advance_hours',
        'subscription_plans',
        'booking_requirements',
        'unit',
        'is_featured',
        'is_active',
        'features',
        'requirements',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'string',
        'requirements' => 'array',
        'subscription_plans' => 'array',
        'booking_requirements' => 'string',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category', 'name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://via.placeholder.com/300x200/00d4aa/ffffff?text=Service';
    }

    public function getImage2UrlAttribute()
    {
        return $this->image_2 ? asset('storage/' . $this->image_2) : null;
    }

    public function getImage3UrlAttribute()
    {
        return $this->image_3 ? asset('storage/' . $this->image_3) : null;
    }

    public function getImage4UrlAttribute()
    {
        return $this->image_4 ? asset('storage/' . $this->image_4) : null;
    }

    public function getFeaturesArrayAttribute()
    {
        if (empty($this->features)) {
            return [];
        }
        
        return array_filter(array_map('trim', explode(',', $this->features)));
    }

    public function getRequirementsArrayAttribute()
    {
        if (is_array($this->requirements)) {
            return $this->requirements;
        }
        
        if (is_string($this->requirements)) {
            // Handle comma-separated string
            return array_filter(array_map('trim', explode(',', $this->requirements)));
        }
        
        return [];
    }

    public function getBookingAdvanceNoticeAttribute()
    {
        return $this->booking_advance_hours . ' hours';
    }

    public function getMainCategoryNameAttribute()
    {
        return match($this->main_category) {
            'one_time' => 'One-time Services',
            'monthly_subscription' => 'Monthly Subscription',
            default => 'Unknown'
        };
    }

    public function isOneTimeService()
    {
        return $this->main_category === 'one_time';
    }

    public function isMonthlySubscription()
    {
        return $this->main_category === 'monthly_subscription';
    }

    public function getSubscriptionPlansArrayAttribute()
    {
        return $this->subscription_plans ?? [];
    }
}