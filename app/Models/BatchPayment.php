<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPayment extends Model
{
    use HasFactory;
    protected $table = 'batch_payments';
    protected $fillable = [
        'batch_id',
        'paid_amount',
        'payment_type',
        'payment_by',
        'note',
    ];
}
