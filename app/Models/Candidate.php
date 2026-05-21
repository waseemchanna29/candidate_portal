<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'batch_id',
        'full_name',
        'phone',
        'cnic',
        'address',
        'city',
        'status',
        'unique_code',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function paymentReceipt()
    {
        return $this->hasOne(PaymentReceipt::class);
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            default     => 'badge-pending',
        };
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
