<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'invoice_no',
        'customer_id',
        'shipping_id',
        'products',
        'total',
        'due',
        'status',
        'profit',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }
    public function payment(){
        return $this->hasMany(InvoicePayment::class,'invoice_id');
    }
}
