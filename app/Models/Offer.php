<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'code',
        'is_active',
        'applicable_products',
        'applicable_services',
        'applicable_categories',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'applicable_products' => 'array',
        'applicable_services' => 'array',
        'applicable_categories' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeValid($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://via.placeholder.com/300x200/ffc107/ffffff?text=Offer';
    }

    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function isUsageLimitReached()
    {
        return $this->usage_limit && $this->usage_count >= $this->usage_limit;
    }
}