<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';
    
    protected $fillable = [
        'candidate_id', 'degree', 'institution',
        'field_of_study', 'start_year', 'end_year', 'grade', 'is_current'
    ];
}
