<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'duration_months',  'description', 'is_active', 'pricing_model_id'];

    protected $casts = ['is_active' => 'boolean'];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

   

    public function getDurationLabelAttribute(): string
    {
        return $this->duration_months . ' ' . ($this->duration_months === 1 ? 'Month' : 'Months');
    }

    public function pricingModel()
    {
        return $this->belongsTo(PricingModel::class);
    }
}
