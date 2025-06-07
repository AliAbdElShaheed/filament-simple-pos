<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    // Fillable properties for the model
    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'sku',
        'description',
        'price',
        'quantity',
        'image',
        'type',
        'is_active',
        'is_visible',
        'is_featured',
        'published_at',
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'brand_id' => 'integer',
            'quantity' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class);
    }
} // end class Product
