<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->price, 2);
    }
}