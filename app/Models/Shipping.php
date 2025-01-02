<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $table = 'shippings';
    protected $fillable = [
        'consignment_id',
        'tracking_code',
        'total',
        'paid',
        'due',
        'cod_amount',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'status',
        'shipping_cost',
        'invoice_no',
        'shipping_status'
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_no');
    }

}
