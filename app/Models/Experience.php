<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $table = 'experiences';
    protected $fillable = [
        'candidate_id',
        'company_name',
        'job_title',
        'description',
        'start_date',
        'end_date',
        'is_current'
    ];
}
