<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'category_id',
        'unit',
        'stock_quantity',
        'sku',
        'is_featured',
        'is_active',
        'specifications',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'specifications' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://via.placeholder.com/300x200/00d4aa/ffffff?text=Product';
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
}