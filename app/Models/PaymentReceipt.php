<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id', 'receipt_number', 'amount',
        'bank_name', 'payment_date', 'receipt_image'
    ];
}
