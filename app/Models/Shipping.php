<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id', 'shipping_company', 'tracking_number',
        'shipping_status', 'shipping_cost', 'shipped_at',
        'delivered_at', 'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
