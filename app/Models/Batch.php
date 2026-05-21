<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'course_code', 'year', 'batch_no',
        'total_seats', 'status', 'start_date', 'end_date', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function approvedCandidates()
    {
        return $this->hasMany(Candidate::class)->where('status', 'approved')->where('is_waitlisted', false);
    }

    public function waitlistedCandidates()
    {
        return $this->hasMany(Candidate::class)->where('is_waitlisted', true);
    }

    public function getSeatsFilledAttribute(): int
    {
        return $this->approvedCandidates()->count();
    }

    public function getSeatsAvailableAttribute(): int
    {
        return max(0, $this->total_seats - $this->seats_filled);
    }

    public function getIsFulAttribute(): bool
    {
        return $this->seats_available <= 0;
    }

    public function getBatchLabelAttribute(): string
    {
        return "{$this->course_code}-{$this->year}-B{$this->batch_no}";
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'open'        => 'badge-approved',
            'full'        => 'badge-warning',
            'in_progress' => 'badge-info',
            'closed'      => 'badge-rejected',
            default       => 'badge-pending',
        };
    }
}